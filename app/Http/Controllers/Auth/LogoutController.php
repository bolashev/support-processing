<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController
{
    public function __invoke(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $gatewayUrl = config('services.bitrix_sso.gateway_url');
        $redirectUrl = $gatewayUrl . '?' . http_build_query(['redirect_to' => config('app.url')]);

        return response()->json([
            'redirect_url' => $redirectUrl,
        ]);
    }
}
