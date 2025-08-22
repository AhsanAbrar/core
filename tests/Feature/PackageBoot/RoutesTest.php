<?php

use Illuminate\Support\Facades\Route;
use Tests\Fixtures\Site\Src\SiteServiceProvider;
use function Pest\Laravel\get;

describe('PackageBoot web routes', function () {
    beforeEach(function () {
        $this->app->register(SiteServiceProvider::class);
    });

    it('should load web routes', function () {
        get('/')->assertOk()->assertSee('site-web-root');

        $uris = collect(Route::getRoutes())->map->uri();
        expect($uris)->toContain('hello');
    });

    it('applies the "web" middleware group', function () {
        get('/hello');

        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'hello');
        expect($route)->not->toBeNull();
        expect(collect($route->gatherMiddleware()))->toContain('web');
    });

    it('does not set domain or controller by default', function () {
        get('/');

        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === '/');
        expect($route->getDomain())->toBeNull();
        expect($route->getAction('controller'))->toBeNull();
    });
});

describe('PackageBoot api routes', function () {
    beforeEach(function () {
        $this->app->register(SiteServiceProvider::class);
    });

    it('uses {Package::key()}/api as the prefix', function () {
        get('/api/ping')->assertOk()->assertJson(['ok' => true]);
    });

    it('applies only the "api" middleware group', function () {
        get('/api/ping');

        $apiRoute = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'api/ping');
        $apiMW = collect($apiRoute->gatherMiddleware());
        expect($apiMW)->toContain('api');
        expect($apiMW)->not->toContain('web');
    });

    it('does not set domain or controller by default', function () {
        get('/api/ping');

        $apiRoute = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'api/ping');
        expect($apiRoute->getDomain())->toBeNull();
        expect($apiRoute->getAction('controller'))->toBeNull();
    });
});
