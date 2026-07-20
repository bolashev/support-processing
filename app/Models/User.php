<?php

namespace App\Models;

use App\Enums\ProcessingDirection;
use App\Models\Traits\SerializeDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin \Eloquent
 *
 * @property int $id
 * @property int|null $bitrix_id
 * @property string|null $uid_1c
 * @property string $name
 * @property string $email
 * @property string|null $avatar_url
 * @property string|null $phone
 * @property string|null $position
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<Role> $roles
 * @property-read Collection<Direction> $directions
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SerializeDate;

    protected $fillable = [
        'bitrix_id',
        'uid_1c',
        'name',
        'email',
        'avatar_url',
        'phone',
        'position',
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function directions(): BelongsToMany
    {
        return $this->belongsToMany(Direction::class, 'user_directions');
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles->contains('slug', $slug);
    }

    public function getDirectionValues(): array
    {
        return $this->directions->pluck('slug')->toArray();
    }

    public function syncDirections(array $directions): void
    {
        $directionIds = [];

        foreach ($directions as $direction) {
            $slug = $direction instanceof ProcessingDirection ? $direction->value : $direction;
            $directionModel = Direction::where('slug', $slug)->first();
            if ($directionModel) {
                $directionIds[] = $directionModel->id;
            }
        }

        $this->directions()->sync($directionIds);
    }

    public function hasDirection(string $direction): bool
    {
        return $this->directions->contains('slug', $direction);
    }

    public function hasAnyDirection(): bool
    {
        return $this->directions()->exists();
    }
}
