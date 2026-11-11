<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
    }
}
