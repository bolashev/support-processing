<?php

namespace App\Data\Dashboard;

use Spatie\LaravelData\Data;

class DashboardData extends Data
{
    public function __construct(
        public int $new_count = 0,
        public int $in_progress_count = 0,
        public int $shipped_count = 0,
    ) {}
}
