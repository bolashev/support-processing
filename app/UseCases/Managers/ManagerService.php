<?php

namespace App\UseCases\Managers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final readonly class ManagerService
{
    public function getList(
        bool $onlyShipped = false,
        ?string $period = null,
        ?string $date_from = null,
        ?string $date_to = null,
    ): Collection {
        $user = auth()->user();
        $directions = $user->getDirectionValues();

        $ordersQuery = Order::whereNotNull('manager_id');
        if ($onlyShipped) {
            $ordersQuery->shipped();
        }
        $ordersQuery->inPeriod($period, $date_from, $date_to);

        if (! $user->hasRole('admin') && ! $user->hasRole('root') && ! empty($directions)) {
            $ordersQuery->where(function (Builder $q) use ($user, $directions) {
                $q->where('manager_id', $user->id)
                    ->orWhereIn('client_type', $directions);
            });
        }

        $managerIds = $ordersQuery->distinct()->pluck('manager_id');

        $query = User::whereHas('roles', fn ($q) => $q->where('slug', 'support_manager'))
            ->whereIn('id', $managerIds)
            ->orderBy('name');

        if (! $user->hasRole('admin') && ! $user->hasRole('root') && ! empty($directions)) {
            $query->whereHas('directions', fn ($q) => $q->whereIn('slug', $directions));
        }

        return $query->get(['id', 'name']);
    }
}
