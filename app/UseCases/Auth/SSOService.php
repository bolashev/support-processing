<?php

namespace App\UseCases\Auth;

use App\Enums\ProcessingDirection;
use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

final readonly class SSOService
{
    public function authenticate(string $token, string $signature): User
    {
        $payload = $this->validateToken($token, $signature);

        $user = User::updateOrCreate(
            ['bitrix_id' => $payload['bitrix_id']],
            [
                'uid_1c' => $payload['uid_1c'] ?? null,
                'name' => $payload['name'] ?? 'Unknown',
                'email' => $payload['email'] ?? 'unknown@portal.trapeza.ru',
                'avatar_url' => $payload['avatar_url'] ?? null,
            ],
        );

        $groups = $payload['groups'] ?? [];
        $position = $payload['position'] ?? '';
        $directions = ProcessingDirection::fromPortalGroups($groups);

        if (empty($directions)) {
            Log::warning('SSO: user has no processing groups', [
                'user_id' => $user->id,
                'bitrix_id' => $user->bitrix_id,
                'groups' => $groups,
            ]);

            $user->syncDirections([]);
            $user->roles()->detach();

            Auth::login($user, true);

            return $user;
        }

        $user->syncDirections($directions);

        $role = $this->resolveRole($directions, $position);
        $this->assignRole($user, $role);

        Auth::login($user, true);

        Log::info('SSO login', [
            'user_id' => $user->id,
            'bitrix_id' => $user->bitrix_id,
            'name' => $user->name,
            'role' => $role->value,
            'directions' => array_map(fn ($d) => $d->value, $directions),
        ]);

        return $user;
    }

    private function validateToken(string $token, string $signature): array
    {
        $secret = config('services.bitrix_sso.secret');
        $expectedSignature = hash_hmac('sha256', $token, $secret);

        if (! hash_equals($expectedSignature, $signature)) {
            throw new \DomainException('Invalid SSO signature');
        }

        $payload = json_decode(base64_decode($token), true);

        if (! $payload || ! isset($payload['bitrix_id'])) {
            throw new \DomainException('Invalid SSO payload');
        }

        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new \DomainException('SSO token expired');
        }

        return $payload;
    }

    private function resolveRole(array $directions, string $position): RoleEnum
    {
        $hasAll = in_array(ProcessingDirection::All, $directions);

        if ($hasAll) {
            return RoleEnum::Root;
        }

        $isTeamlead = str_contains(strtolower($position), 'тимлид');

        return $isTeamlead ? RoleEnum::Admin : RoleEnum::SupportManager;
    }

    private function assignRole(User $user, RoleEnum $role): void
    {
        $roleModel = Role::firstOrCreate(
            ['slug' => $role->value],
            ['name' => $role->label()],
        );

        $user->roles()->sync([$roleModel->id]);
    }
}
