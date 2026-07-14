<?php

namespace App\Data\Orders;

use App\Enums\OrderRequestStatus;
use Spatie\LaravelData\Data;

class OrderListData extends Data
{
    public function __construct(
        public ?string $search = null,
        public ?OrderRequestStatus $request_status = null,
        public ?int $manager_id = null,
        public ?string $manager_filter = null,
        public int $per_page = 50,
    ) {}
}
