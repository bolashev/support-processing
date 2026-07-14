<?php

namespace App\Http\Controllers;

use App\Data\Notes\NoteUpdateData;
use App\Http\Requests\Notes\NoteStoreRequest;
use App\Http\Requests\Notes\NoteUpdateRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\UseCases\Notes\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    public function __construct(
        private readonly NoteService $notes,
    ) {}

    public function index(Request $request): JsonResponse
    {
        return $this->handleException(fn () =>
            NoteResource::collection($this->notes->getList($request->user()->id))
        );
    }

    public function store(NoteStoreRequest $request): JsonResponse
    {
        return $this->handleTransaction(fn () =>
            new NoteResource($this->notes->create(
                $request->user()->id,
                $request->data(),
            ))
        );
    }

    public function update(Note $note, NoteUpdateRequest $request): JsonResponse
    {
        return $this->handleTransaction(fn () =>
            new NoteResource($this->notes->update(
                NoteUpdateData::from([
                    'note' => $note,
                    'user_id' => $request->user()->id,
                    'body' => $request->input('body'),
                ]),
            ))
        );
    }

    public function destroy(Note $note, Request $request): JsonResponse
    {
        return $this->handleTransaction(function () use ($note, $request) {
            $this->notes->delete($note->id, $request->user()->id);

            return response()->json(null, Response::HTTP_NO_CONTENT);
        });
    }
}
