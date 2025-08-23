<?php

namespace Spanvel\Package;

use Spanvel\SpanvelServiceProvider;

class PackageContext
{
    /**
     * The current package key.
     */
    protected string $key = '';

    /**
     * Get a registrar instance for the given package base path.
     */
    public function boot(string $packagePath): PackageBoot
    {
        return new PackageBoot($packagePath);
    }

    /**
     * Get or set the current package key.
     */
    public function key(?string $key = null): string
    {
        if ($key !== null) {
            $this->key = $key;
        }

        return $this->key;
    }

    /**
     * Set the current package key.
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Reset the package key.
     */
    public function reset(): void
    {
        $this->key = '';
    }

    /**
     * Register all packages service providers.
     */
    public static function registerAllProviders(): void
    {
        app()->register(SpanvelServiceProvider::class);

        foreach (config('packages.providers') as $provider) {
            app()->register($provider);
        }
    }
}
