<?php

namespace App\Http\Controllers;

use App\Data\Archive\ArchiveListData;
use App\Http\Requests\Archive\ArchiveListRequest;
use App\Http\Resources\ArchiveOrderResource;
use App\UseCases\Archive\ArchiveService;

class ArchiveController extends Controller
{
    public function __construct(
        private readonly ArchiveService $archive,
    ) {}

    public function index(ArchiveListRequest $request)
    {
        return $this->handleException(fn () =>
            ArchiveOrderResource::collection($this->archive->getList(
                new ArchiveListData(
                    search: $request['search'],
                    manager_id: $request['manager_id'] ? (int) $request['manager_id'] : null,
                    date_from: $request['dateFrom'],
                    date_to: $request['dateTo'],
                ),
            ))
        );
    }
}
