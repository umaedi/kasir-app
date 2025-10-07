<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TokenStorageService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TokenStorageService::class, function () {
            return new TokenStorageService();
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
