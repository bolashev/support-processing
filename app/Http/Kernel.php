<?php

namespace App\Http;

use Illuminate\Foundation\Configuration\Middleware;

class Kernel
{
    protected array $aliases = [];

    public function __invoke(Middleware $middleware)
    {
        if ($this->aliases) {
            $middleware->alias($this->aliases);
        }

        return $middleware;
    }
}
