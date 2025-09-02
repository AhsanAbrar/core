<?php

namespace Spanvel;

use Illuminate\Support\ServiceProvider;
use Spanvel\Console\InstallCommand;
use Spanvel\Console\PackageCommand;

class SpanvelServiceProvider extends ServiceProvider
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
        // Publish the config file
        $this->publishes([
            __DIR__.'/../config/packages.php' => config_path('packages.php'),
        ], 'spanvel-config');

        // Publish the migration files
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'spanvel-migrations');

        // Register the install command for CLI
        $this->commands([
            InstallCommand::class,
            PackageCommand::class,
        ]);
    }
}
