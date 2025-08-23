<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\Fixtures\Site\Http\Controllers\DemoController;
use Tests\Fixtures\Site\Src\SiteServiceProviderWithDomainController;

beforeEach(function () {
    $this->app->register(SiteServiceProviderWithDomainController::class);
});

describe('PackageBoot webRoutes domain and controller options', function () {
    it('uses the configured domain and controller for /', function () {
        $route = Route::getRoutes()->match(
            Request::create('/', 'GET', [], [], [], ['HTTP_HOST' => 'web.test'])
        );

        expect($route->uri())->toBe('/');
        expect($route->getDomain())->toBe('web.test');
        expect($route->methods())->toContain('GET');

        $action = $route->getAction();

        // Support both "controller" => "FQCN@method" and "uses" => [FQCN, 'method']
        $controllerFqcn = $action['controller'] ?? (is_array($action['uses'] ?? null) ? $action['uses'][0] : null);
        expect($controllerFqcn)->toBe(DemoController::class);
    });
});

describe('PackageBoot apiRoutes domain and controller options', function () {
    it('uses the configured domain and controller for /api/ping', function () {
        $route = Route::getRoutes()->match(
            Request::create('/api/ping', 'GET', [], [], [], ['HTTP_HOST' => 'api.test'])
        );

        expect($route->uri())->toBe('api/ping');
        expect($route->getDomain())->toBe('api.test');
        expect($route->methods())->toContain('GET');

        $action = $route->getAction();
        $controllerFqcn = $action['controller'] ?? (is_array($action['uses'] ?? null) ? $action['uses'][0] : null);
        expect($controllerFqcn)->toBe(DemoController::class);
    });
});
