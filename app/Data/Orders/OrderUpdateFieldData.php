<?php

namespace App\Data\Orders;

use App\Models\Order;
use Spatie\LaravelData\Data;

class OrderUpdateFieldData extends Data
{
    public function __construct(
        public Order $order,
        public int $user_id,
        public string $field,
        public mixed $value,
    ) {}
}
