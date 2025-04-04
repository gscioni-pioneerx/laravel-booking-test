<?php

namespace App\Providers;

use App\Repositories\BookingRepository;
use App\Services\BookingService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BookingService::class, function (Application $app) {
            return new BookingService(
                $app->make(BookingRepository::class)
            );
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
