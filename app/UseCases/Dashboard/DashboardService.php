<?php

namespace App\UseCases\Dashboard;

use App\Data\Dashboard\DashboardData;
use App\Enums\OrderRequestStatus;
use App\Models\Order;

final readonly class DashboardService
{
    public function getCounters(): DashboardData
    {
        $user = auth()->user();

        $baseQuery = $user && ! $user->hasRole('admin')
            ? Order::query()->where(function ($q) use ($user) {
                $q->where('manager_id', $user->id)
                    ->orWhereNull('manager_id');
            })
            : Order::query();

        return new DashboardData(
            new_count: (clone $baseQuery)->byRequestStatus(OrderRequestStatus::New)->count(),
            in_progress_count: (clone $baseQuery)->byRequestStatus(OrderRequestStatus::InProgress)->count(),
            shipped_count: (clone $baseQuery)->byRequestStatus(OrderRequestStatus::Completed)->count(),
        );
    }
}
