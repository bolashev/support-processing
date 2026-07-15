<?php

namespace App\Http\Resources;

use App\Enums\OrderRequestStatus;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
class OrderDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bitrix_id' => $this->bitrix_id,
            'number' => $this->number,
            'request_status' => $this->request_status->value,
            'request_status_label' => $this->request_status->label(),
            'request_status_color' => $this->mapStateColor($this->request_status),
            'order_status' => $this->order_status->value,
            'order_status_label' => $this->order_status->label(),
            'order_status_type' => $this->mapStatusType(),
            'channel' => $this->channel?->value,
            'channel_label' => $this->channel?->label(),

            'counterparty_name' => $this->counterparty_name,
            'counterparty_partner' => $this->counterparty_partner,
            'amount' => $this->amount,

            'payment_method' => $this->payment_method,
            'delivery_method' => $this->delivery_method,
            'delivery_details' => $this->delivery_details,
            'delivery_cost' => $this->delivery_cost,
            'delivery_became_paid' => $this->delivery_became_paid,

            'sales_manager_name' => $this->sales_manager_name,
            'sales_manager_email' => $this->sales_manager_email,
            'sales_manager_phone' => $this->sales_manager_phone,

            'client_phone' => $this->client_phone,
            'client_email' => $this->client_email,
            'client_inn' => $this->client_inn,
            'client_company' => $this->client_company,

            'processing_at' => $this->processing_at?->format('Y-m-d H:i:s'),
            'assigned_at' => $this->assigned_at?->format('Y-m-d H:i:s'),
            'shipped_at' => $this->shipped_at?->format('Y-m-d H:i:s'),
            'reserve_date' => $this->reserve_date?->format('Y-m-d H:i:s'),

            'debt_control_disabled' => $this->debt_control_disabled,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),

            'manager' => $this->whenLoaded('manager', fn () => [
                'id' => $this->manager->id,
                'name' => $this->manager->name,
                'email' => $this->manager->email,
                'phone' => $this->manager->phone,
                'position' => $this->manager->position,
            ]),

            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'documents' => OrderDocumentResource::collection($this->whenLoaded('documents')),
            'comments' => OrderCommentResource::collection($this->whenLoaded('comments')),
            'history' => OrderHistoryResource::collection($this->whenLoaded('history')),
        ];
    }

    private function mapStateColor(OrderRequestStatus $status): string
    {
        return match ($status) {
            OrderRequestStatus::New => 'green',
            OrderRequestStatus::InProgress => 'orange',
            OrderRequestStatus::Completed => 'purple',
        };
    }

    private function mapStatusType(): string
    {
        return match ($this->order_status) {
            OrderStatus::Open => 'open',
            OrderStatus::AwaitingPayment => 'wait',
            OrderStatus::OpenPaidPercent => 'paid',
            OrderStatus::OpenShippedPercent => 'shipped',
            OrderStatus::ClosedShipped100 => 'closed',
            OrderStatus::ClosedShippedPercent => 'closed',
            OrderStatus::Cancelled => 'cancelled',
            OrderStatus::CreatedTransfer => 'transfer',
            OrderStatus::InTransit => 'transit',
        };
    }
}
