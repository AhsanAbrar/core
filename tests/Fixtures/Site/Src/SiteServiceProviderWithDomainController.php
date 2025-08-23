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
            ->views()
            ->webRoutes(
                domain: 'web.test',
                controller: \Tests\Fixtures\Site\Http\Controllers\DemoController::class,
            )
            ->apiRoutes(
                domain: 'api.test',
                controller: \Tests\Fixtures\Site\Http\Controllers\DemoController::class,
            );
    }
}
