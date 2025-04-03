<?php

namespace App\Providers;

use App\Repositories\BookingRepository;
use App\Services\BookingService;
use App\Services\LoggingService;
use App\Strategies\BookingStatus\BookingStatusContext;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(BookingRepository::class, function ($app) {
            return new BookingRepository();
        });

        $this->app->singleton(LoggingService::class, function ($app) {
            return new LoggingService();
        });

        $this->app->singleton(BookingStatusContext::class, function ($app) {
            return new BookingStatusContext();
        });

        $this->app->singleton(BookingService::class, function ($app) {
            return new BookingService(
                $app->make(BookingRepository::class),
                $app->make(LoggingService::class),
                $app->make(BookingStatusContext::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}