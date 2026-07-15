<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManagerResource;
use App\UseCases\Managers\ManagerService;

class ManagerController extends Controller
{
    public function __construct(
        private readonly ManagerService $managers,
    ) {}

    public function index()
    {
        return $this->handleException(fn () =>
            ManagerResource::collection($this->managers->getList())
        );
    }
}
