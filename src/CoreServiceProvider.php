<?php

namespace Spanvel;

use Illuminate\Support\ServiceProvider;
// use Spanvel\Http\Middleware\ServePackage;
// use Illuminate\Contracts\Http\Kernel as HttpKernel;

class SpanCoreServiceProvider extends ServiceProvider
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
        // if (! $this->app->configurationIsCached()) {
        //     $this->mergeConfigFrom(__DIR__.'/../config/span.php', 'span');
        // }

        // if ($this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
        //     Span::registerAllProviders();
        // }

        // $this->app->make(HttpKernel::class)
        //             ->pushMiddleware(ServePackage::class);
    }
}
