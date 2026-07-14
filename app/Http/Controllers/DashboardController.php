<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardResource;
use App\UseCases\Dashboard\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
    ) {}

    public function index(): JsonResponse
    {
        return $this->handleException(fn () =>
            new DashboardResource($this->dashboard->getCounters())
        );
    }
}
