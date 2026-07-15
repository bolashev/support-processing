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
 * @property int $order_id
 * @property int $position
 * @property string|null $product_code
 * @property string $name
 * @property int $quantity
 * @property float $price
 * @property string|null $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Order $order
 */
class OrderItem extends Model
{
    use HasFactory, SerializeDate;

    protected $guarded = ['_token', '_method'];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
