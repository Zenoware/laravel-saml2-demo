<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use OneLogin\Saml2\Utils as Saml2Utils;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        if ($this->isUsingBuiltInServer() && $request->headers->has('x-forwarded-host')) {
            $protocol = $request->headers->get('x-forwarded-proto');
            if ($protocol === 'https') {
                Saml2Utils::setSelfProtocol('https');
                URL::forceScheme($protocol);
            }
        }
    }

    private function isUsingBuiltInServer(): bool
    {
        return str_contains($_SERVER['SERVER_SOFTWARE'], 'PHP') &&
            str_contains($_SERVER['SERVER_SOFTWARE'], 'Development Server');
    }
}
