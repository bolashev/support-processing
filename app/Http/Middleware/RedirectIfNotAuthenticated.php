<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class RedirectIfNotAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if (config('app.env') === 'local') {
                $user = User::firstOrCreate(
                    ['bitrix_id' => 1],
                    ['name' => 'Денис Краев', 'email' => 'denis@trapeza.ru', 'role' => 'admin']
                );
                Auth::login($user);
            } else {
                return redirect()->route('sso.redirect');
            }
        }

        return $next($request);
    }
}
