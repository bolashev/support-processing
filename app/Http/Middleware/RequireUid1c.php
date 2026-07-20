<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireUid1c
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && empty($user->uid_1c)) {
            return response()->json([
                'message' => 'В вашей учётной записи на портале отсутствует ID 1С. Обратитесь к администратору',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
