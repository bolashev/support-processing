<?php

namespace App\Http\Controllers;

use App\Http\Requests\Archive\ArchiveListRequest;
use App\Http\Resources\ArchiveOrderResource;
use App\UseCases\Archive\ArchiveService;
use Illuminate\Http\JsonResponse;

class ArchiveController extends Controller
{
    public function __construct(
        private readonly ArchiveService $archive,
    ) {}

    public function index(ArchiveListRequest $request): JsonResponse
    {
        return $this->handleException(fn () =>
            ArchiveOrderResource::collection($this->archive->getList($request->data()))
        );
    }
}
