<?php

use App\Http\Controllers\Auth\SSOController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::get('sso', [SSOController::class, 'redirect'])
        ->name('sso.redirect');

    Route::get('sso/callback', [SSOController::class, 'callback'])
        ->name('sso.callback');
});

Route::middleware('auth.sso')->group(function () {
    Route::get('/{any?}', function (\Illuminate\Http\Request $request) {
        return view('app', ['user' => $request->user()]);
    })->where('any', '.*');
});
