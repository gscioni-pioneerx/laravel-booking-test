<?php

namespace App\Providers;

use App\Repositories\BookingRepository;
use App\Repositories\CustomerRepository;
use App\Services\AppLogService;
use App\Services\BookingService;
use App\Services\CustomerService;
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

        $this->app->singleton(CustomerService::class, function (Application $app) {
            return new CustomerService(
                $app->make(CustomerRepository::class)
            );
        });

        $this->app->singleton(AppLogService::class, function (Application $app) {
            return new AppLogService();
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
