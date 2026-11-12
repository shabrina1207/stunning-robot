<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\RateLimiter;

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
    public function boot(): void
    {
        // Vercel terminates TLS at the edge. Force generated form/action URLs
        // to remain HTTPS even when Laravel receives the request internally
        // through an HTTP proxy.
        if ($this->app->environment('production') || env('VERCEL')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(
                strtolower((string) $request->input('email')).'|'.$request->ip()
            );
        });

        RateLimiter::for('orders', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });
    }
}
