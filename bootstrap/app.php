<?php

use App\Console\Kernel as ScheduleKernel;
use App\Exceptions\Handler;
use App\Http\Kernel;
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withSchedule(new ScheduleKernel())
    ->withMiddleware(new Kernel())
    ->withExceptions(new Handler())->create();
