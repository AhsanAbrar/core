<?php

namespace Auth;

use Illuminate\Support\ServiceProvider;
use Spanvel\Support\Facades\Package;

class AuthServiceProvider extends ServiceProvider
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
        Package::boot(__DIR__)
            ->views('auth')
            ->webRoutes();

        BladeComponents::register();
    }
}
