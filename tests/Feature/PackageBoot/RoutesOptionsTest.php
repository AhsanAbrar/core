<?php

use Illuminate\Support\Facades\Route;
use Tests\Fixtures\Site\Src\SiteServiceProviderWithRoutesOptions;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->app->register(SiteServiceProviderWithRoutesOptions::class);
});

describe('PackageBoot webRoutes custom options', function () {
    it('respects a custom string prefix', function () {
        $this->withoutMiddleware();
        get('/foo')->assertOk()->assertSee('site-web-root');
    });

    it('respects a custom middleware array (e.g., ["web","auth"])', function () {
        get('/foo');

        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'foo');
        $mw = collect($route->gatherMiddleware());
        expect($mw)->toContain('web');
        expect($mw)->toContain('auth');
    });
});

describe('PackageBoot apiRoutes custom options', function () {
    it('respects a custom string prefix', function () {
        $this->withoutMiddleware();
        get('/custom-api/ping')->assertOk()->assertJson(['ok' => true]);
    });

    it('respects a custom middleware array (api + throttle) and not web', function () {
        get('/custom-api/ping');

        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'custom-api/ping');
        expect($route)->not->toBeNull();

        $mw = collect($route->gatherMiddleware());
        expect($mw)->toContain('api');
        expect($mw)->not->toContain('web');
    });
});
