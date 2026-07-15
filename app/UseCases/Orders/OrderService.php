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

        return $order->refresh();
    }

    public function return(OrderReturnData $data): Order
    {
        $order = $data->order;

        if ($order->request_status !== OrderRequestStatus::InProgress) {
            throw new \DomainException('Можно вернуть только заявку в работе');
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

        return $order->refresh();
    }

    public function updateField(OrderUpdateFieldData $data): Order
    {
        $order = $data->order;

        $allowedFields = [
            'payment_method', 'delivery_method', 'client_phone',
            'client_email', 'reserve_date', 'counterparty_name',
        ];

        if (! in_array($data->field, $allowedFields, true)) {
            throw new \DomainException("Поле {$data->field} нельзя редактировать");
        }

        $oldValue = $order->{$data->field};
        $order->update([$data->field => $data->value]);

        $order->history()->create([
            'user_id' => $data->user_id,
            'action' => 'field_updated',
            'old_values' => [$data->field => $oldValue],
            'new_values' => [$data->field => $data->value],
        ]);

        return $order->refresh();
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

        return $order->refresh();
    }
}
