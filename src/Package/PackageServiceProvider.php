<?php

namespace Spanvel\Package;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton('spanvel.package', fn () => new Factory);
    }

    public function provides(): array
    {
        return ['spanvel.package'];
    }
}
