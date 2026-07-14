<?php

namespace App\UseCases\Dashboard;

use App\Data\Dashboard\DashboardData;
use App\Enums\OrderRequestStatus;
use App\Models\Order;

final readonly class DashboardService
{
    public function getCounters(): DashboardData
    {
        return new DashboardData(
            new_count: Order::byRequestStatus(OrderRequestStatus::New)->count(),
            in_progress_count: Order::byRequestStatus(OrderRequestStatus::InProgress)->count(),
            shipped_count: Order::byRequestStatus(OrderRequestStatus::Completed)->count(),
        );
    }
}
