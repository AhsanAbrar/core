<?php

use Illuminate\Support\Facades\Route;
use Tests\Fixtures\Site\Src\SiteServiceProvider;
use function Pest\Laravel\get;

describe('PackageBoot routes', function () {
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
