<?php

namespace App\UseCases\Notes;

use App\Data\Notes\NoteStoreData;
use App\Data\Notes\NoteUpdateData;
use App\Models\Note;
use Illuminate\Support\Collection;

final readonly class NoteService
{
    public function getList(int $userId): Collection
    {
        return Note::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create(int $userId, NoteStoreData $data): Note
    {
        return Note::create([
            'user_id' => $userId,
            'body' => $data->body,
        ]);
    }

    public function update(NoteUpdateData $data): Note
    {
        $note = $data->note;

        if ($note->user_id !== $data->user_id) {
            throw new \DomainException('Заметка не найдена');
        }

        $note->update(['body' => $data->body]);

        return $note->refresh();
    }

    public function delete(int $noteId, int $userId): void
    {
        $note = Note::where('id', $noteId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $note->delete();
    }
}
