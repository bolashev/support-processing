<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.sso')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/managers', [ManagerController::class, 'index']);

    Route::apiResource('notes', NoteController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders/{id}/take', [OrderController::class, 'take']);
    Route::post('/orders/{order}/return', [OrderController::class, 'return']);
    Route::put('/orders/{order}/field', [OrderController::class, 'updateField']);
    Route::post('/orders/{order}/comment', [OrderController::class, 'addComment']);

    Route::get('/archive', [ArchiveController::class, 'index']);
});
