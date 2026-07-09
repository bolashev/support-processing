<?php

namespace App\Http;

use App\Http\Middleware\RedirectIfNotAuthenticated;
use Illuminate\Foundation\Configuration\Middleware;

class Kernel
{
    protected array $aliases = [
        'auth.sso' => RedirectIfNotAuthenticated::class,
    ];

    public function __invoke(Middleware $middleware)
    {
        if ($this->aliases) {
            $middleware->alias($this->aliases);
        }

        return $middleware;
    }
}
