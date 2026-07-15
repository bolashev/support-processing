<?php

namespace App\Http\Controllers;

use App\Data\Notes\NoteStoreData;
use App\Data\Notes\NoteUpdateData;
use App\Http\Requests\Notes\NoteStoreRequest;
use App\Http\Requests\Notes\NoteUpdateRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\UseCases\Notes\NoteService;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    public function __construct(
        private readonly NoteService $notes,
    ) {}

    public function index()
    {
        return $this->handleException(fn () =>
            NoteResource::collection($this->notes->getList(auth()->id()))
        );
    }

    public function store(NoteStoreRequest $request)
    {
        return $this->handleTransaction(fn () =>
            new NoteResource($this->notes->create(
                auth()->id(),
                new NoteStoreData(body: $request['body']),
            ))
        );
    }

    public function update(Note $note, NoteUpdateRequest $request)
    {
        return $this->handleTransaction(fn () =>
            new NoteResource($this->notes->update(
                new NoteUpdateData(
                    note: $note,
                    user_id: auth()->id(),
                    body: $request['body'],
                ),
            ))
        );
    }

    public function destroy(Note $note)
    {
        return $this->handleTransaction(function () use ($note) {
            $this->notes->delete($note->id, auth()->id());

            return response()->json(null, Response::HTTP_NO_CONTENT);
        });
    }
}
