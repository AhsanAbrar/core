<?php

use Illuminate\Support\Facades\Route;
use Tests\Fixtures\Site\Src\SiteServiceProviderWithWebRoutesOptions;
use function Pest\Laravel\get;

describe('PackageBoot webRoutes custom options', function () {
    beforeEach(function () {
        $this->app->register(SiteServiceProviderWithWebRoutesOptions::class);
    });

    it('respects a custom string prefix', function () {
        $this->withoutMiddleware();
        get('foo')->assertOk()->assertSee('site-web-root');
    });

    it('respects a custom middleware array (e.g., ["web","auth"])', function () {
        get('foo');

        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'foo');
        $mw = collect($route->gatherMiddleware());
        expect($mw)->toContain('web');
        expect($mw)->toContain('auth');
    });
});

describe('PackageBoot apiRoutes custom options', function () {
    beforeEach(function () {
        $this->app->register(SiteServiceProviderWithWebRoutesOptions::class);
    });

    it('respects a custom string prefix', function () {
        $this->withoutMiddleware();
        get('foo')->assertOk()->assertSee('site-web-root');
    });

    it('respects a custom middleware array (e.g., ["web","auth"])', function () {
        get('foo');

        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'foo');
        $mw = collect($route->gatherMiddleware());
        expect($mw)->toContain('web');
        expect($mw)->toContain('auth');
    });
});
