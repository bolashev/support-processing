<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
class ArchiveOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'order_status' => $this->order_status->value,
            'order_status_label' => $this->order_status->label(),
            'processing_time' => $this->getProcessingTime(),
            'shipped_at' => $this->shipped_at?->format('Y-m-d H:i:s'),
            'manager_name' => $this->whenLoaded('manager', fn () => $this->manager->name),
        ];
    }

    private function getProcessingTime(): ?string
    {
        if (! $this->assigned_at || ! $this->shipped_at) {
            return null;
        }

        $seconds = (int) $this->assigned_at->diffInSeconds($this->shipped_at);
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        if ($hours > 0) {
            return sprintf('%d:%02d ч.', $hours, $minutes);
        }

        return sprintf('%d мин.', $minutes);
    }
}
