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
 * @property int|null $user_id
 * @property string $action
 * @property array|null $old_values
 * @property array|null $new_values
 * @property string|null $comment
 * @property Carbon|null $created_at
 *
 * @property-read Order $order
 * @property-read User|null $user
 */
class OrderHistory extends Model
{
    use HasFactory;

    protected $table = 'order_history';

    public $timestamps = false;

    public $timestamps = false;

    protected $guarded = ['_token', '_method'];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
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
