<?php

use Illuminate\Support\Facades\Route;
use Spanvel\Support\Facades\Package;
use Tests\Fixtures\Site\SiteServiceProvider;
use Tests\Fixtures\Site\SiteServiceProviderWithOptions;
use Tests\Fixtures\Site\Http\Controllers\DemoController;

describe('PackageBoot :: webRoutes (defaults)', function () {
    beforeEach(function () {
        Package::setKey('site');
        $this->app->register(SiteServiceProvider::class);
    });

    it('uses Package::key() as the prefix', function () {
        $this->get('/site')->assertOk()->assertSee('site-web-root');
        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'site');
        expect($route)->not->toBeNull();
    });

    it('applies the "web" middleware group', function () {
        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'site');
        expect(collect($route->gatherMiddleware()))->toContain('web');
    });

    it('does not set domain or controller by default', function () {
        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'site');
        expect($route->getDomain())->toBeNull();
        expect($route->getAction('controller'))->toBeNull();
    });

    it('responds on /{key}/hello and renders package views', function () {
        $this->get('/site/hello')->assertOk()->assertSee('From Package View', false);
    });
});

describe('PackageBoot :: webRoutes (custom options)', function () {
    beforeEach(function () {
        Package::setKey('site'); // api still uses this
        $this->app->register(SiteServiceProviderWithOptions::class);
    });

    it('respects a custom string prefix', function () {
        $this->withoutMiddleware();
        $this->get('/foo')->assertOk()->assertSee('site-web-root');
    });

    it('respects a custom middleware array (e.g., ["web","auth"])', function () {
        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'foo');
        $mw = collect($route->gatherMiddleware());
        expect($mw)->toContain('web');
        expect($mw)->toContain('auth');
    });

    it('allows setting a default controller via group attribute', function () {
        // Dynamically load a controller-grouped file with controller attribute
        $boot = new \Spanvel\Package\PackageBoot(__DIR__.'/../Fixtures/Site');
        $boot->routes('ctrl.php', middleware: ['web'], prefix: 'ctrl', domain: null, controller: DemoController::class);

        $this->get('/ctrl')->assertOk()->assertSee('demo-index');
        $this->get('/ctrl/about')->assertOk()->assertSee('demo-about');

        $r = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'ctrl');
        expect($r)->not->toBeNull();
        // Action will be resolved to [Controller@method] by the router.
        expect($r->getAction('controller'))->not->toBeNull();
        expect($r->gatherMiddleware())->toContain('web');
    });

    it('does not affect API routes when custom web options are used', function () {
        $this->get('/site/api/ping')->assertOk()->assertJson(['ok' => true]);
        $apiRoute = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'site/api/ping');
        $apiMW = collect($apiRoute->gatherMiddleware());
        expect($apiMW)->toContain('api');
        expect($apiMW)->not->toContain('web');
        expect($apiMW)->not->toContain('auth');
    });
});

describe('PackageBoot :: apiRoutes (defaults)', function () {
    beforeEach(function () {
        Package::setKey('site');
        $this->app->register(SiteServiceProvider::class);
    });

    it('uses {Package::key()}/api as the prefix', function () {
        $this->get('/site/api/ping')->assertOk()->assertJson(['ok' => true]);
    });

    it('applies only the "api" middleware group', function () {
        $apiRoute = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'site/api/ping');
        $apiMW = collect($apiRoute->gatherMiddleware());
        expect($apiMW)->toContain('api');
        expect($apiMW)->not->toContain('web');
    });

    it('does not set domain or controller by default', function () {
        $apiRoute = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'site/api/ping');
        expect($apiRoute->getDomain())->toBeNull();
        expect($apiRoute->getAction('controller'))->toBeNull();
    });
});

describe('PackageBoot :: apiRoutes (custom options)', function () {
    beforeEach(function () {
        Package::setKey('site');
        $this->app->register(SiteServiceProvider::class);
    });

    it('respects a custom string prefix', function () {
        $boot = \Spanvel\Package\PackageBoot::make(__DIR__.'/../Fixtures/Site');
        $boot->apiRoutes(middleware: 'api', prefix: 'custom-api');
        $this->get('/custom-api/ping')->assertOk()->assertJson(['ok' => true]);
    });

    it('respects a custom middleware array (e.g., ["api","throttle:60,1"])', function () {
        $boot = new \Spanvel\Package\PackageBoot(__DIR__.'/../Fixtures/Site');
        $boot->apiRoutes(middleware: ['api', 'throttle:60,1'], prefix: 'throttled-api');
        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'throttled-api/ping');
        $mw = collect($route->gatherMiddleware());
        expect($mw)->toContain('api');
        expect($mw->implode(','))->toContain('throttle:60,1');
    });

    it('respects a default controller for the group', function () {
        $boot = new \Spanvel\Package\PackageBoot(__DIR__.'/../Fixtures/Site');
        $boot->apiRoutes(middleware: 'api', prefix: 'api-ctrl', controller: DemoController::class);
        // Our routes/api.php only defines /ping via closure; to exercise controller grouping,
        // we can also load ctrl.php under api-style prefix:
        $boot->routes('ctrl.php', middleware: 'api', prefix: 'api-ctrl');

        $this->get('/api-ctrl')->assertOk()->assertSee('demo-index');
        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'api-ctrl');
        expect($route->getAction('controller'))->not->toBeNull();
        expect(collect($route->gatherMiddleware()))->toContain('api');
    });
});

describe('PackageBoot :: web + api together', function () {
    beforeEach(function () {
        Package::setKey('site');
        $this->app->register(SiteServiceProvider::class);
    });

    it('registers both groups without collision', function () {
        $this->get('/site')->assertOk();
        $this->get('/site/api/ping')->assertOk();
    });

    it('keeps middleware scopes isolated between web and api', function () {
        $web = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'site');
        $api = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'site/api/ping');
        expect(collect($web->gatherMiddleware()))->toContain('web');
        expect(collect($api->gatherMiddleware()))->toContain('api');
    });

    it('is order-independent (apiRoutes before webRoutes)', function () {
        $boot = new \Spanvel\Package\PackageBoot(__DIR__.'/../Fixtures/Site');
        $boot->apiRoutes();
        $boot->webRoutes();

        $this->get('/site')->assertOk();
        $this->get('/site/api/ping')->assertOk();
    });
});

describe('PackageBoot :: missing/invalid package path', function () {
    it('no-ops and skips routes when the base path is invalid', function () {
        $boot = new \Spanvel\Package\PackageBoot('/path/that/does/not/exist');
        // Should not throw:
        $boot->routes('web.php', middleware: 'web', prefix: 'x');
        // Route shouldn't exist:
        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'x');
        expect($route)->toBeNull();
    });

    it('supports make() and constructor equally even with a bad path (no crash)', function () {
        $a = \Spanvel\Package\PackageBoot::make('/bad/path/');
        $b = new \Spanvel\Package\PackageBoot('/bad/path');
        // Calling typical methods should not crash:
        $a->views()->webRoutes(prefix: 'wa'); // will skip silently
        $b->apiRoutes(prefix: 'ba');          // will skip silently

        // No routes registered for these bogus prefixes:
        $wa = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'wa');
        $ba = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'ba/ping');
        expect($wa)->toBeNull();
        expect($ba)->toBeNull();
    });
});
