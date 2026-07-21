<?php

namespace App\Data\Archive;

use Spatie\LaravelData\Data;

class ArchiveListData extends Data
{
    public function __construct(
        public ?string $search = null,
        public ?array $manager_ids = null,
        public ?string $period = null,
        public ?string $date_from = null,
        public ?string $date_to = null,
        public ?string $sort = null,
        public ?string $dir = null,
        public int $per_page = 20,
    ) {}
}
