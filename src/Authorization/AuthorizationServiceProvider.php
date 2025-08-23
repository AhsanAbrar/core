<?php

namespace Spanvel\Authorization;

use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
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
    public function boot(Authorization $authorization): void
    {
        $authorization->handle();
    }
}
