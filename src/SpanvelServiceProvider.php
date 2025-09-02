<?php

namespace Spanvel;

use Illuminate\Support\ServiceProvider;
use Spanvel\Console\InstallCommand;
use Spanvel\Console\PackageCommand;
use Spanvel\Support\Contracts\Option as OptionContract;
use Spanvel\Support\Option;

class SpanvelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OptionContract::class, fn () => new Option);
        $this->app->alias(OptionContract::class, 'option');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publish the config file
        $this->publishes([
            __DIR__.'/../config/packages.php' => config_path('packages.php'),
        ], 'spanvel-config');

        // Register the install command for CLI
        $this->commands([
            InstallCommand::class,
            PackageCommand::class,
        ]);
    }
}
