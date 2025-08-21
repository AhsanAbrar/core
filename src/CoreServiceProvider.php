<?php

namespace Spanvel;

use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Support\ServiceProvider;
// use Spanvel\Http\Middleware\ServePackage;
use Spanvel\Http\Middleware\ResolvePackage;

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
        if ($this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
            Package::registerAllProviders();
        }

        // $this->app->make(HttpKernel::class)
            // ->pushMiddleware(ServePackage::class);

        $this->app->make(HttpKernel::class)
            ->pushMiddleware(ResolvePackage::class);
    }
}
