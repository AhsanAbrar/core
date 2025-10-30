<?php

namespace Tests\Fixtures\Site\Src;

use Illuminate\Support\ServiceProvider;
use Spanvel\Support\Facades\Package;

class SiteServiceProviderWithDomainController extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Package::boot(__DIR__)
            ->webRoutes(
                domain: 'web.test',
            )
            ->apiRoutes(
                domain: 'api.test',
            );
    }
}
