<?php

namespace Spanvel\Package;

use Spanvel\SpanvelServiceProvider;

class PackageRegistrar
{
    /**
     * Register all packages service providers.
     */
    public static function registerAllProviders(): void
    {
        app()->register(SpanvelServiceProvider::class);

        foreach (config('packages.providers', []) as $provider) {
            app()->register($provider);
        }
    }
}
