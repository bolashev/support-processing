<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;

class AccountController extends Controller
{
    public function index()
    {
        return $this->handleException(fn () =>
            new AccountResource(auth()->user()->load('roles'))
        );
    }
}
