<?php

namespace App\UseCases\Managers;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

final readonly class ManagerService
{
    public function getList(): Collection
    {
        $managerIds = Order::whereNotNull('manager_id')
            ->distinct()
            ->pluck('manager_id');

        return User::where('role', UserRole::SupportManager)
            ->whereIn('id', $managerIds)
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
