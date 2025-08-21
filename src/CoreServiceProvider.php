<?php

namespace Spanvel;

use Illuminate\Support\ServiceProvider;
// use Spanvel\Http\Middleware\ServePackage;
// use Illuminate\Contracts\Http\Kernel as HttpKernel;

class CoreServiceProvider extends ServiceProvider
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
        // if ($this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
        //     Span::registerAllProviders();
        // }

        // $this->app->make(HttpKernel::class)
        //             ->pushMiddleware(ServePackage::class);
    }
}
