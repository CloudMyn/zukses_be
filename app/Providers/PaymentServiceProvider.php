<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Midtrans\MidtransService;
use App\Services\Midtrans\MidtransHttpClient;
use App\Services\Midtrans\MidtransValidator;
use App\Services\Midtrans\MidtransSecurity;
use App\Services\Midtrans\PaymentTransactionManager;

/**
 * PaymentServiceProvider
 *
 * Service Provider untuk payment system Midtrans
 * Mendaftarkan semua payment services dan bindings
 *
 * @author Zukses Development Team
 * @version 1.0.0
 */
class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Midtrans Security Service
        $this->app->singleton(MidtransSecurity::class, function ($app) {
            return new MidtransSecurity();
        });

        // Midtrans Validator Service
        $this->app->singleton(MidtransValidator::class, function ($app) {
            return new MidtransValidator();
        });

        // Midtrans HTTP Client Service
        $this->app->singleton(MidtransHttpClient::class, function ($app) {
            return new MidtransHttpClient();
        });

        // Midtrans Service (main service)
        $this->app->singleton(MidtransService::class, function ($app) {
            return new MidtransService(
                $app->make(MidtransHttpClient::class),
                $app->make(MidtransValidator::class),
                $app->make(MidtransSecurity::class)
            );
        });

        // Payment Transaction Manager Service
        $this->app->singleton(PaymentTransactionManager::class, function ($app) {
            return new PaymentTransactionManager(
                $app->make(MidtransService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Register payment configurations
        $this->publishes([
            __DIR__ . '/../../config/midtrans.php' => config_path('midtrans.php'),
        ], 'midtrans-config');

        // Register migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/midtrans.php',
            'midtrans'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            MidtransService::class,
            MidtransHttpClient::class,
            MidtransValidator::class,
            MidtransSecurity::class,
            PaymentTransactionManager::class,
        ];
    }
}