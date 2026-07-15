<?php

namespace App\UseCases\Managers;

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

        return User::whereHas('roles', fn ($q) => $q->where('slug', 'support_manager'))
            ->whereIn('id', $managerIds)
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
