<?php

namespace Tests\Fixtures\Site\Src;

use Illuminate\Support\ServiceProvider;
use Spanvel\Support\Facades\Package;

class SiteServiceProviderWithRoutesOptions extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Package::boot(__DIR__)
            ->views()
            ->webRoutes(
                middleware: ['web', 'auth'],
                prefix: 'foo',
            )
            ->apiRoutes(
                middleware: ['api', 'auth'],
                prefix: 'custom-api',
            );
    }
}
