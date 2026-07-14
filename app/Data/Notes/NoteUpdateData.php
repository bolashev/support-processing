<?php

namespace App\Data\Notes;

use App\Models\Note;
use Spatie\LaravelData\Data;

class NoteUpdateData extends Data
{
    public function __construct(
        public Note $note,
        public int $user_id,
        public string $body,
    ) {}
}
