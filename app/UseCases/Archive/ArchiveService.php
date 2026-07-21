<?php

namespace App\UseCases\Archive;

use App\Data\Archive\ArchiveListData;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final readonly class ArchiveService
{
    public function getList(ArchiveListData $data): LengthAwarePaginator
    {
        $user = auth()->user();

        $query = Order::with(['manager'])
            ->shipped()
            ->when($data->search, function (Builder $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('number', 'like', "%{$search}%")
                        ->orWhere('counterparty_name', 'like', "%{$search}%");
                });
            })
            ->when($data->manager_ids, function (Builder $query, array $managerIds) {
                $query->whereIn('manager_id', $managerIds);
            })
            ->when($user && ! $user->hasRole('admin') && ! $user->hasRole('root'), function (Builder $query) use ($user) {
                $directions = $user->getDirectionValues();
                if (! empty($directions)) {
                    $query->where(function (Builder $q) use ($user, $directions) {
                        $q->where('manager_id', $user->id)
                            ->orWhereIn('client_type', $directions);
                    });
                }
            })
            ->inPeriod($data->period, $data->date_from, $data->date_to);

        return $this->applySort($query, $data)
            ->paginate($data->per_page);
    }

    public function hasAny(User $user): bool
    {
        return Order::shipped()
            ->when(! $user->hasRole('admin') && ! $user->hasRole('root'), function (Builder $query) use ($user) {
                $directions = $user->getDirectionValues();
                if (! empty($directions)) {
                    $query->where(function (Builder $q) use ($user, $directions) {
                        $q->where('manager_id', $user->id)
                            ->orWhereIn('client_type', $directions);
                    });
                }
            })
            ->exists();
    }

    private function applySort(Builder $query, ArchiveListData $data): Builder
    {
        $direction = $data->dir === 'asc' ? 'asc' : 'desc';

        return match ($data->sort) {
            'processing_time' => $query->orderByRaw(
                "TIMESTAMPDIFF(SECOND, assigned_at, shipped_at) {$direction}"
            ),
            'shipped_at' => $query->orderBy('shipped_at', $direction),
            default => $query->orderBy('shipped_at', 'desc'),
        };
    }
}
