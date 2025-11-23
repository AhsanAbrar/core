<?php

namespace Spanvel\Package;

class Factory
{
    /**
     * Entry point for users: Package::boot(__DIR__, key: 'blog') → Bootstrapper
     */
    public function boot(string $basePath, ?string $key = null): Bootstrapper
    {
        return new Bootstrapper(basePath: $basePath, key: $key);
    }
}
