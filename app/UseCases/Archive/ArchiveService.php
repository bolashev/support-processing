<?php

namespace App\UseCases\Archive;

use App\Data\Archive\ArchiveListData;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final readonly class ArchiveService
{
    public function getList(ArchiveListData $data): LengthAwarePaginator
    {
        $query = Order::with(['manager'])
            ->shipped()
            ->when($data->search, function (Builder $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('number', 'like', "%{$search}%")
                        ->orWhere('counterparty_name', 'like', "%{$search}%");
                });
            })
            ->when($data->manager_id, function (Builder $query, int $managerId) {
                $query->forManager($managerId);
            })
            ->when($data->date_from, function (Builder $query, string $dateFrom) {
                $query->where('shipped_at', '>=', $dateFrom);
            })
            ->when($data->date_to, function (Builder $query, string $dateTo) {
                $query->where('shipped_at', '<=', $dateTo . ' 23:59:59');
            });

        return $query->orderBy('shipped_at', 'desc')
            ->paginate(50);
    }
}
