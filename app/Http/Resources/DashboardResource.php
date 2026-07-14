<?php

namespace App\Http\Resources;

use App\Data\Dashboard\DashboardData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DashboardData
 */
class DashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'new_count' => $this->new_count,
            'in_progress_count' => $this->in_progress_count,
            'shipped_count' => $this->shipped_count,
        ];
    }
}
