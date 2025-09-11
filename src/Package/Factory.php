<?php

namespace Spanvel\Package;

class Factory
{
    /**
     * Entry point for users: Package::boot(__DIR__) → Bootstrapper
     */
    public function boot(string $packagePath): Bootstrapper
    {
        return new Bootstrapper($packagePath);
    }
}
