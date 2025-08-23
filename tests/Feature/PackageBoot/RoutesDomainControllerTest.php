<?php

use Illuminate\Support\Facades\Route;
use Tests\Fixtures\Site\Src\SiteServiceProviderWithDomainController;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->app->register(SiteServiceProviderWithDomainController::class);
});

describe('PackageBoot webRoutes domain and controller options', function () {
    it('respects a custom domain', function () {
        get('/');

        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === '/');
        expect($route->getDomain())->toBe('web.test');
    });

    it('respects a custom controller', function () {
        get('/');

        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === '/');
        expect($route->getAction('controller'))
            ->toBe(\Tests\Fixtures\Site\Http\Controllers\DemoController::class);
    });
});

describe('PackageBoot apiRoutes domain and controller options', function () {
    it('respects a custom domain', function () {
        get('/api/ping');

        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'api/ping');
        expect($route->getDomain())->toBe('api.test');
    });

    it('respects a custom controller', function () {
        get('/api/ping');

        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'api/ping');
        expect($route->getAction('controller'))
            ->toBe(\Tests\Fixtures\Site\Http\Controllers\DemoController::class);
    });
});
