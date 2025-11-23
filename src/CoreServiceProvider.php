<?php

namespace Spanvel;

use Illuminate\Support\ServiceProvider;
use Spanvel\Option\OptionServiceProvider;
use Spanvel\Package\PackageServiceProvider;
use Spanvel\Support\SupportServiceProvider;

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
                Console\OptionCommand::class,
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
            OptionServiceProvider::class,
            SupportServiceProvider::class,
        ];

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
}
