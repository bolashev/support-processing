<?php

namespace App\Models;

use App\Enums\ClientType;
use App\Enums\OrderChannel;
use App\Enums\OrderRequestStatus;
use App\Enums\OrderStatus;
use App\Models\Traits\SerializeDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Eloquent
 *
 * @property int $id
 * @property int|null $bitrix_id
 * @property string $number
 * @property OrderRequestStatus $request_status
 * @property OrderStatus $order_status
 * @property int|null $manager_id
 * @property string|null $counterparty_name
 * @property string|null $counterparty_partner
 * @property float $amount
 * @property string|null $payment_method
 * @property string|null $delivery_method
 * @property string|null $delivery_details
 * @property float|null $delivery_cost
 * @property bool $delivery_became_paid
 * @property OrderChannel|null $channel
 * @property ClientType|null $client_type
 * @property string|null $sales_manager_name
 * @property string|null $sales_manager_email
 * @property string|null $sales_manager_phone
 * @property string|null $client_phone
 * @property string|null $client_email
 * @property string|null $client_inn
 * @property string|null $client_company
 * @property Carbon|null $processing_at
 * @property Carbon|null $assigned_at
 * @property Carbon|null $shipped_at
 * @property Carbon|null $reserve_date_start_at
 * @property Carbon|null $reserve_date_end_at
 * @property bool $debt_control_disabled
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User|null $manager
 * @property-read Carbon|null $waiting_time
 * @property-read Carbon|null $working_time
 *
 * @method static Builder|Order byRequestStatus(OrderRequestStatus $status)
 * @method static Builder|Order byOrderStatus(OrderStatus $status)
 * @method static Builder|Order forManager(int $userId)
 * @method static Builder|Order shipped()
 * @method static Builder|Order byClientTypes(array $clientTypes)
 */
class Order extends Model
{
    use HasFactory, SerializeDate;

    protected $guarded = ['_token', '_method'];

    protected function casts(): array
    {
        return [
            'request_status' => OrderRequestStatus::class,
            'order_status' => OrderStatus::class,
            'channel' => OrderChannel::class,
            'client_type' => ClientType::class,
            'amount' => 'decimal:2',
            'delivery_cost' => 'decimal:2',
            'delivery_became_paid' => 'boolean',
            'debt_control_disabled' => 'boolean',
            'processing_at' => 'datetime',
            'assigned_at' => 'datetime',
            'shipped_at' => 'datetime',
            'reserve_date_start_at' => 'datetime',
            'reserve_date_end_at' => 'datetime',
        ];
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OrderDocument::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(OrderComment::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function scopeByRequestStatus(Builder $query, OrderRequestStatus $status): Builder
    {
        return $query->where('request_status', $status);
    }

    public function scopeByOrderStatus(Builder $query, OrderStatus $status): Builder
    {
        return $query->where('order_status', $status);
    }

    public function scopeForManager(Builder $query, int $userId): Builder
    {
        return $query->where('manager_id', $userId);
    }

    public function scopeShipped(Builder $query): Builder
    {
        return $query->where('request_status', OrderRequestStatus::Completed);
    }

    public function scopeByClientTypes(Builder $query, array $clientTypes): Builder
    {
        return $query->whereIn('client_type', $clientTypes);
    }
}
