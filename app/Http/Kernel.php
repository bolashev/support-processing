<?php

namespace App\Http;

use App\Http\Middleware\CheckProcessingAccess;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Middleware\RequireUid1c;
use Illuminate\Foundation\Configuration\Middleware;

class Kernel
{
    protected array $aliases = [
        'auth.sso' => RedirectIfNotAuthenticated::class,
        'processing.access' => CheckProcessingAccess::class,
        'require.uid1c' => RequireUid1c::class,
    ];

    public function __invoke(Middleware $middleware)
    {
        if ($this->aliases) {
            $middleware->alias($this->aliases);
        }

        $middleware->statefulApi();

        return $middleware;
    }
}
