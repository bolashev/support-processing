<?php

namespace App\Http\Controllers;

use App\Data\Archive\ArchiveListData;
use App\Http\Requests\Archive\ArchiveListRequest;
use App\Http\Resources\ArchiveOrderResource;
use App\Models\User;
use App\UseCases\Archive\ArchiveService;

class ArchiveController extends Controller
{
    public function __construct(
        private readonly ArchiveService $archive,
    ) {}

    public function index(ArchiveListRequest $request)
    {
        return $this->handleException(function () use ($request) {
            $data = new ArchiveListData(
                search: $request['search'],
                manager_ids: $request['manager_ids'],
                period: $request['period'],
                date_from: $request['date_from'],
                date_to: $request['date_to'],
                sort: $request['sort'],
                dir: $request['dir'],
                per_page: $request['per_page'] ?? 20,
            );

            $paginator = $this->archive->getList($data);
            $user = $request->user();
            $hasAny = $user instanceof User
                ? $this->archive->hasAny($user)
                : $paginator->total() > 0;

            return ArchiveOrderResource::collection($paginator)
                ->additional(['meta' => ['has_any' => $hasAny]]);
        });
    }
}
