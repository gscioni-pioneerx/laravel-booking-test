<?php

namespace App\Providers;

use App\Repositories\BookingRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(BookingRepository::class, function (Application $app) {
            return new BookingRepository();
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
