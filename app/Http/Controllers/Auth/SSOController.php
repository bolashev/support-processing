<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

final class SSOController
{
    public function redirect(Request $request): RedirectResponse
    {
        $gatewayUrl = config('services.bitrix_sso.gateway_url');
        $appUrl = config('app.url');

        $url = $gatewayUrl . '?' . http_build_query([
            'redirect_to' => $appUrl,
        ]);

        return redirect()->away($url);
    }

    public function callback(Request $request): RedirectResponse
    {
        $token = (string) $request->string('token');
        $signature = (string) $request->string('signature');

        if ($token === '' || $signature === '') {
            Log::warning('SSO callback: missing token or signature', [
                'ip' => $request->ip(),
            ]);

            return redirect()->route('sso.redirect');
        }

        $secret = config('services.bitrix_sso.secret');
        $expectedSignature = hash_hmac('sha256', $token, $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('SSO callback: invalid signature', [
                'token' => $token,
            ]);

            return redirect()->route('sso.redirect');
        }

        $payload = json_decode(base64_decode($token), true);

        if (!$payload || !isset($payload['bitrix_id'])) {
            Log::warning('SSO callback: invalid payload');

            return redirect()->route('sso.redirect');
        }

        if (isset($payload['exp']) && $payload['exp'] < time()) {
            Log::warning('SSO callback: token expired', [
                'bitrix_id' => $payload['bitrix_id'],
            ]);

            return redirect()->route('sso.redirect');
        }

        $user = User::updateOrCreate(
            ['bitrix_id' => $payload['bitrix_id']],
            [
                'uid_1c'     => $payload['uid_1c'] ?? null,
                'name'       => $payload['name'] ?? 'Unknown',
                'email'      => $payload['email'] ?? 'unknown@portal.trapeza.ru',
                'avatar_url' => $payload['avatar_url'] ?? null,
            ],
        );

        $supportRole = Role::firstOrCreate(['slug' => 'support_manager'], ['name' => 'Менеджер поддержки']);
        if (!$user->roles->contains($supportRole)) {
            $user->roles()->attach($supportRole);
        }

        Auth::login($user, true);

        Log::info('SSO login', [
            'user_id'   => $user->id,
            'bitrix_id' => $user->bitrix_id,
            'name'      => $user->name,
        ]);

        return redirect()->intended('/');
    }
}
