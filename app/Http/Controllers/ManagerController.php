<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManagerResource;
use App\UseCases\Managers\ManagerService;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function __construct(
        private readonly ManagerService $managers,
    ) {}

    public function index(Request $request)
    {
        return $this->handleException(fn () =>
            ManagerResource::collection($this->managers->getList(
                $request->boolean('shipped'),
                $request['period'],
                $request['date_from'],
                $request['date_to'],
            ))
        );
    }
}
