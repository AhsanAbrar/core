<?php

namespace Spanvel;

class Package
{
    /**
     * Current Package Key.
     */
    public static string $key = '';

    /**
     * Create a new Span builder instance.
     */
    public static function boot(string $packagePath): PackageBoot
    {
        return PackageBoot::make($packagePath);
    }

    /**
     * Register all span service providers.
     */
    public static function registerAllProviders(): void
    {
        // app()->register(SpanServiceProvider::class);

        // foreach (config('packages.providers') as $key => $provider) {
        //     app()->register($provider);
        // }
    }

    /**
     * Set and get current key on runtime.
     *
     * @return string
     */
    public static function key($key = null)
    {
        return $key ? static::$key = $key : static::$key;
    }
}
