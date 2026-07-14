<?php

namespace App\Data\Notes;

use Spatie\LaravelData\Data;

class NoteStoreData extends Data
{
    public function __construct(
        public string $body,
    ) {}
}
