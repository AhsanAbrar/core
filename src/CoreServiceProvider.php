<?php

namespace Spanvel;

use Illuminate\Support\ServiceProvider;
use Spanvel\Package\PackageServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerProviders();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\PackageCommand::class,
            ]);
        }
    }

    /**
     * Register the required service providers.
     */
    protected function registerProviders(): void
    {
        $providers = [
            PackageServiceProvider::class,
        ];

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
}
