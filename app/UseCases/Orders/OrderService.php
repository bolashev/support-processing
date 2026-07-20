<?php

namespace App\UseCases\Orders;

use App\Data\Orders\OrderCommentData;
use App\Data\Orders\OrderListData;
use App\Data\Orders\OrderReturnData;
use App\Data\Orders\OrderUpdateFieldData;
use App\Enums\OrderRequestStatus;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final readonly class OrderService
{
    public function getList(OrderListData $data): LengthAwarePaginator
    {
        $user = auth()->user();

        $query = Order::with(['manager', 'comments'])
            ->when($data->search, function (Builder $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('number', 'like', "%{$search}%")
                        ->orWhere('counterparty_name', 'like', "%{$search}%");
                });
            })
            ->when($data->request_status, function (Builder $query, OrderRequestStatus $status) {
                $query->byRequestStatus($status);
            })
            ->when($data->manager_ids, function (Builder $query, array $managerIds) {
                $query->whereIn('manager_id', $managerIds);
            })
            ->when($user && ! $user->hasRole('admin') && ! $user->hasRole('root'), function (Builder $query) use ($user) {
                $directions = $user->getDirectionValues();
                if (! empty($directions)) {
                    $query->where(function (Builder $q) use ($user, $directions) {
                        $q->where('manager_id', $user->id)
                            ->orWhereIn('client_type', $directions);
                    });
                }
            });

        return $query->when($data->shipped_sort, function (Builder $query, string $direction) {
            $query->orderBy('shipped_at', $direction);
        })
            ->orderBy('created_at', 'desc')
            ->paginate($data->per_page);
    }

    public function getById(int $id): Order
    {
        return Order::with(['manager', 'items', 'documents', 'comments.user', 'history.user'])
            ->findOrFail($id);
    }

    public function take(int $orderId, int $userId): Order
    {
        $order = Order::findOrFail($orderId);

        if ($order->request_status !== OrderRequestStatus::New) {
            throw new \DomainException('Заказ уже взят в работу');
        }

        $user = auth()->user();
        $directions = $user->getDirectionValues();

        if (! $user->hasRole('admin') && ! $user->hasRole('root')
            && ! empty($directions) && $order->client_type
            && ! in_array($order->client_type->value, $directions)) {
            throw new \DomainException('Заказ не принадлежит вашему направлению');
        }

        $order->update([
            'request_status' => OrderRequestStatus::InProgress,
            'manager_id' => $userId,
            'assigned_at' => now(),
        ]);

        $order->history()->create([
            'user_id' => $userId,
            'action' => 'taken',
            'old_values' => ['request_status' => OrderRequestStatus::New->value],
            'new_values' => ['request_status' => OrderRequestStatus::InProgress->value],
        ]);

        return $order->refresh()->load('manager');
    }

    public function return(OrderReturnData $data): Order
    {
        $order = $data->order;

        if ($order->request_status !== OrderRequestStatus::InProgress) {
            throw new \DomainException('Можно вернуть только заявку в работе');
        }

        if ($order->manager_id !== $data->user_id) {
            throw new \DomainException('Можно вернуть только свой заказ');
        }

        $order->update([
            'request_status' => OrderRequestStatus::New,
            'manager_id' => null,
            'assigned_at' => null,
        ]);

        $order->history()->create([
            'user_id' => $data->user_id,
            'action' => 'returned',
            'old_values' => ['request_status' => OrderRequestStatus::InProgress->value],
            'new_values' => ['request_status' => OrderRequestStatus::New->value],
            'comment' => $data->comment,
        ]);

        if ($data->comment) {
            $order->comments()->create([
                'user_id' => $data->user_id,
                'body' => $data->comment,
            ]);
        }

        return $order->refresh()->load('manager');
    }

    public function updateField(OrderUpdateFieldData $data): void
    {
        $order = $data->order;

        if ($order->manager_id !== $data->user_id) {
            throw new \DomainException('Можно редактировать только свой заказ');
        }

        $allowedFields = [
            'payment_method', 'delivery_method', 'client_phone',
            'client_email', 'reserve_range', 'counterparty_name',
        ];

        if (! in_array($data->field, $allowedFields, true)) {
            throw new \DomainException("Поле {$data->field} нельзя редактировать");
        }

        if ($data->field === 'reserve_range') {
            $this->saveReserveRange($order, $data->value, $data->user_id);

            return;
        }

        $oldValue = $order->{$data->field};
        $order->update([$data->field => $data->value]);

        $order->history()->create([
            'user_id' => $data->user_id,
            'action' => 'field_updated',
            'old_values' => [$data->field => $oldValue],
            'new_values' => [$data->field => $data->value],
        ]);
    }

    private function saveReserveRange(Order $order, ?string $value, int $userId): void
    {
        $oldStart = $order->reserve_date_start_at?->format('Y-m-d');
        $oldEnd = $order->reserve_date_end_at?->format('Y-m-d');
        $oldValue = $oldStart && $oldEnd ? "{$oldStart} — {$oldEnd}" : $oldStart;

        $startAt = null;
        $endAt = null;

        if ($value) {
            $parts = array_map('trim', explode('—', $value));
            $startAt = $parts[0] ? $parts[0].' 00:00:00' : null;
            $endAt = ($parts[1] ?? $parts[0]) ? ($parts[1] ?? $parts[0]).' 23:59:59' : null;
        }

        $order->update([
            'reserve_date_start_at' => $startAt,
            'reserve_date_end_at' => $endAt,
        ]);

        $order->history()->create([
            'user_id' => $userId,
            'action' => 'field_updated',
            'old_values' => ['reserve_range' => $oldValue],
            'new_values' => ['reserve_range' => $value],
        ]);
    }

    public function addComment(OrderCommentData $data): Order
    {
        $order = $data->order;

        $order->comments()->create([
            'user_id' => $data->user_id,
            'body' => $data->body,
            'is_internal' => $data->is_internal,
        ]);

        $order->history()->create([
            'user_id' => $data->user_id,
            'action' => 'comment_added',
            'new_values' => ['body' => $data->body, 'is_internal' => $data->is_internal],
        ]);

        return $order->refresh()->load('manager');
    }
}
