<?php

namespace App\Models;

use App\Models\Traits\SerializeDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<User> $users
 */
class Direction extends Model
{
    use SerializeDate;

    protected $guarded = ['_token', '_method'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_directions');
    }
}
