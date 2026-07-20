<?php

namespace App\Http\Controllers\Auth;

use App\UseCases\Auth\SSOService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final readonly class SSOController
{
    public function __construct(
        private SSOService $sso,
    ) {}

    public function redirect(Request $request): RedirectResponse
    {
        $gatewayUrl = config('services.bitrix_sso.gateway_url');
        $appUrl = config('app.url');

        $url = $gatewayUrl.'?'.http_build_query([
            'redirect_to' => $appUrl,
        ]);

        return redirect()->away($url);
    }

    public function callback(Request $request): RedirectResponse
    {
        $token = (string) $request->string('token');
        $signature = (string) $request->string('signature');

        $user = $this->sso->authenticate($token, $signature);

        if (! $user->hasAnyDirection()) {
            return redirect(config('services.bitrix_sso.portal_url'));
        }

        return redirect()->intended('/');
    }
}
