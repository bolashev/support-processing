<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property string $body
 * @property bool $is_internal
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Order $order
 * @property-read User $user
 */
class OrderComment extends Model
{
    use HasFactory;

    protected $guarded = ['_token', '_method'];

    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
