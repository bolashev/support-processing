<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardResource;
use App\UseCases\Dashboard\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
    ) {}

    public function index()
    {
        return $this->handleException(fn () =>
            new DashboardResource($this->dashboard->getCounters())
        );
    }
}
