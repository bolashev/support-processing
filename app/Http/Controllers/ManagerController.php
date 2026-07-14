<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManagerResource;
use App\UseCases\Managers\ManagerService;
use Illuminate\Http\JsonResponse;

class ManagerController extends Controller
{
    public function __construct(
        private readonly ManagerService $managers,
    ) {}

    public function index(): JsonResponse
    {
        return $this->handleException(fn () =>
            ManagerResource::collection($this->managers->getList())
        );
    }
}
