<?php

namespace App\Models;

use App\Models\Traits\SerializeDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $bitrix_id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $position
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Role[] $roles
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SerializeDate;

    protected $fillable = [
        'bitrix_id',
        'name',
        'email',
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

    public function hasRole(string $slug): bool
    {
        return $this->roles->contains('slug', $slug);
    }
}
