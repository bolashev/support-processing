<?php

namespace App\Data\Orders;

use App\Enums\OrderRequestStatus;
use Spatie\LaravelData\Data;

class OrderListData extends Data
{
    public function __construct(
        public ?string $search = null,
        public ?OrderRequestStatus $request_status = null,
        public ?array $manager_ids = null,
        public ?string $shipped_sort = null,
        public int $per_page = 50,
    ) {}
}
