<?php

namespace App\Data\Archive;

use Spatie\LaravelData\Data;

class ArchiveListData extends Data
{
    public function __construct(
        public ?string $search = null,
        public ?int $manager_id = null,
        public ?string $date_from = null,
        public ?string $date_to = null,
    ) {}
}
