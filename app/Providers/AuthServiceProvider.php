<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('manage-order', function (User $user) {
            return ! $user->hasRole('admin');
        });

        Gate::define('view-all-orders', function (User $user) {
            return $user->hasRole('admin');
        });
    }
}
