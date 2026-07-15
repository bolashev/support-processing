<?php

namespace App\Http\Resources;

use App\Enums\OrderRequestStatus;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Order;

/**
 * @mixin Order
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $requestStatus = $this->request_status;

        return [
            'id' => $this->id,
            'number' => $this->number,
            'counterparty' => $this->counterparty_name,
            'order_status_label' => $this->order_status->label(),
            'order_status_type' => $this->mapStatusType(),
            'request_status_label' => $requestStatus->label(),
            'request_status_color' => $this->mapStateColor($requestStatus),
            'timer_label' => $this->getTimerLabel($requestStatus),
            'timer' => $this->getTimer($requestStatus),
            'timer_color' => $this->getTimerColor($requestStatus),
            'manager' => $this->whenLoaded('manager', fn () => $this->manager->name),
            'is_self' => $this->manager_id === $request->user()?->id,
            'messages' => $this->whenCounted('comments'),
            'date' => $this->created_at?->translatedFormat('j F'),
            'time' => $this->created_at?->format('H:i'),
        ];
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

    private function mapStateColor(OrderRequestStatus $status): string
    {
        return match ($status) {
            OrderRequestStatus::New => 'green',
            OrderRequestStatus::InProgress => 'orange',
            OrderRequestStatus::Completed => 'purple',
        };
    }

    private function getTimerLabel(OrderRequestStatus $status): string
    {
        return match ($status) {
            OrderRequestStatus::New => 'Ожидание:',
            OrderRequestStatus::InProgress => 'В работе:',
            OrderRequestStatus::Completed => 'Время обработки:',
        };
    }

    private function getTimer(OrderRequestStatus $status): ?string
    {
        return match ($status) {
            OrderRequestStatus::New => $this->processing_at
                ? $this->formatWorkingTime($this->processing_at)
                : null,
            OrderRequestStatus::InProgress => $this->assigned_at
                ? $this->formatWorkingTime($this->assigned_at)
                : null,
            OrderRequestStatus::Completed => ($this->assigned_at && $this->shipped_at)
                ? $this->formatWorkingTimeDiff($this->assigned_at, $this->shipped_at)
                : null,
        };
    }

    private function getTimerColor(OrderRequestStatus $status): string
    {
        return match ($status) {
            OrderRequestStatus::New => $this->processing_at
                ? $this->colorByMinutes($this->processing_at, [5, 10])
                : 'green',
            OrderRequestStatus::InProgress => $this->assigned_at
                ? $this->colorByMinutes($this->assigned_at, [90, 90], true)
                : 'green',
            OrderRequestStatus::Completed => 'default',
        };
    }

    private function colorByMinutes(\Carbon\Carbon $from, array $thresholds, bool $useYellowOnly = false): string
    {
        $minutes = (int) $from->diffInSeconds(now()) / 60;

        if ($useYellowOnly) {
            return $minutes >= $thresholds[0] ? 'orange' : 'green';
        }

        if ($minutes >= $thresholds[1]) {
            return 'red';
        }

        return $minutes >= $thresholds[0] ? 'orange' : 'green';
    }

    private function formatWorkingTime(\Carbon\Carbon $from): string
    {
        $seconds = (int) $from->diffInSeconds(now());
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        if ($hours > 0) {
            return sprintf('%d:%02d ч.', $hours, $minutes);
        }

        return sprintf('%d мин.', $minutes);
    }

    private function formatWorkingTimeDiff(\Carbon\Carbon $start, \Carbon\Carbon $end): string
    {
        $seconds = (int) $start->diffInSeconds($end);
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        if ($hours > 0) {
            return sprintf('%d:%02d ч.', $hours, $minutes);
        }

        return sprintf('%d мин.', $minutes);
    }
}
