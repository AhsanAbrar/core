<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\Fixtures\Site\Src\SiteServiceProviderWithDomainController;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->app->register(SiteServiceProviderWithDomainController::class);
});

describe('PackageBoot webRoutes domain and controller options', function () {
    it('uses the configured domain and controller for /', function () {
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_HOST' => 'web.test']);
        $route = Route::getRoutes()->match($request);

        expect($route->uri())->toBe('/');
        expect($route->getDomain())->toBe('web.test');
        expect($route->getAction('controller'))
            ->toBe(\Tests\Fixtures\Site\Http\Controllers\DemoController::class);
    });
});

describe('PackageBoot apiRoutes domain and controller options', function () {
    it('uses the configured domain and controller for /api/ping', function () {
        $request = Request::create('/api/ping', 'GET', [], [], [], ['HTTP_HOST' => 'api.test']);
        $route = Route::getRoutes()->match($request);

        expect($route->uri())->toBe('api/ping');
        expect($route->getDomain())->toBe('api.test');
        expect($route->getAction('controller'))
            ->toBe(\Tests\Fixtures\Site\Http\Controllers\DemoController::class);
    });
});
