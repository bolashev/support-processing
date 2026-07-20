<?php

namespace App\UseCases\Managers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

final readonly class ManagerService
{
    public function getList(): Collection
    {
        $user = auth()->user();
        $directions = $user->getDirectionValues();

        $managerIds = Order::whereNotNull('manager_id')
            ->distinct()
            ->pluck('manager_id');

        $query = User::whereHas('roles', fn ($q) => $q->where('slug', 'support_manager'))
            ->whereIn('id', $managerIds)
            ->orderBy('name');

        if (! $user->hasRole('admin') && ! $user->hasRole('root') && ! empty($directions)) {
            $query->whereHas('directions', fn ($q) => $q->whereIn('slug', $directions));
        }

        return $query->get(['id', 'name']);
    }
}
