<?php

use Illuminate\Support\Facades\Route;
use Tests\Fixtures\Site\Src\SiteServiceProvider;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->app->register(SiteServiceProvider::class);
});

describe('PackageBoot web routes', function () {
    it('loads web routes and renders package view', function () {
        get('/')->assertOk()->assertSee('site-web-root');
        get('/hello')->assertOk()->assertSee('From Package View', false);

        $uris = collect(Route::getRoutes())->map->uri();
        expect($uris)->toContain('hello');
    });

    it('applies the web middleware group', function () {
        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'hello');
        expect($route)->not->toBeNull();
        expect(collect($route->gatherMiddleware()))->toContain('web');
    });

    it('does not set domain or controller by default', function () {
        $root = collect(Route::getRoutes())->first(
            fn ($r) => in_array($r->uri(), ['', '/'], true)
        );

        expect($root)->not->toBeNull();
        expect($root->getDomain())->toBeNull();
        expect($root->getAction('controller'))->toBeNull();
    });
});

describe('PackageBoot api routes', function () {
    it('mounts api under /api and responds', function () {
        get('/api/ping')->assertOk()->assertJson(['ok' => true]);
    });

    it('applies only api middleware (no web)', function () {
        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'api/ping');
        expect($route)->not->toBeNull();

        $mw = collect($route->gatherMiddleware());
        expect($mw)->toContain('api');
        expect($mw)->not->toContain('web');
    });

    it('does not set domain or controller by default', function () {
        $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === 'api/ping');
        expect($route)->not->toBeNull();
        expect($route->getDomain())->toBeNull();
        expect($route->getAction('controller'))->toBeNull();
    });
});
