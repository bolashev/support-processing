<?php

namespace App\Models;

use App\Models\Traits\SerializeDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $body
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 */
class Note extends Model
{
    use HasFactory, SerializeDate;

    protected $guarded = ['_token', '_method'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
