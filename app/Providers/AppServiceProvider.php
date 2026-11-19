<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const CONTACT_FORM_SUBMISSIONS_PER_WINDOW = 5;

    private const CONTACT_FORM_WINDOW_MINUTES = 10;

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
        RateLimiter::for('contact-form', function (Request $request): Limit {
            return Limit::perMinutes(self::CONTACT_FORM_WINDOW_MINUTES, self::CONTACT_FORM_SUBMISSIONS_PER_WINDOW)
                ->by($request->ip());
        });
    }
}
