<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OtpService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind OtpService to the container
        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
