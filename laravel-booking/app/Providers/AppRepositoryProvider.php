<?php

namespace App\Providers;

use App\Repositories\BookingRepository;
use App\Repositories\CustomerRepository;
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
            return new BookingRepository;
        });

        $this->app->singleton(CustomerRepository::class, function (Application $app) {
            return new CustomerRepository;
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
