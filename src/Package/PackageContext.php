<?php

namespace Spanvel\Package;

class PackageContext
{
    /**
     * The current package key for this request lifecycle.
     */
    protected string $key = '';

    /**
     * Create a fluent registrar for the given package base path.
     *
     * The path should point to the package's root directory (typically __DIR__).
     * From this base, conventional subpaths like "resources/views" and "routes/*.php"
     * will be resolved by the registrar.
     *
     * Example:
     *  Package::boot(__DIR__)->views()->webRoutes()->apiRoutes();
     */
    public function boot(string $packagePath): PackageBoot
    {
        return new PackageBoot($packagePath);
    }

    /**
     * Get or set the current package key.
     *
     * When $key is provided, it will be set and the new value returned.
     * When omitted, the current key will be returned.
     */
    public function key(?string $key = null): string
    {
        if ($key !== null) {
            $this->key = $key;
        }

        return $this->key;
    }

    /**
     * Explicitly set the current package key.
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Reset the current package key to the default.
     */
    public function reset(): void
    {
        $this->key = '';
    }
}
