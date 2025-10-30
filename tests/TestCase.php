<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Register package service providers.
     */
    protected function getPackageProviders($app): array
    {
        return [
            \Spanvel\CoreServiceProvider::class,
        ];
    }

    /**
     * Load package migrations.
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
