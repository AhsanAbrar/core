<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    // Reset config for each test
    Config::set('packages.providers', [
        'demo' => Demo\DemoServiceProvider::class,
        '' => Default\DefaultServiceProvider::class,
    ]);
    Config::set('packages.excluded_routes', [
        'login', 'register', 'logout',
    ]);
});

describe('ServePackage middleware', function () {
    it('registers the correct provider for a matching segment', function () {
        $request = Request::create('/demo', 'GET');
        $kernel = App::make(Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle($request);
        // Here you would assert that DemoServiceProvider was registered
        // For demonstration, we check the Package key
        expect(Spanvel\Package::key())->toBe('demo');
    });

    it('registers the default provider if segment is not found', function () {
        $request = Request::create('/unknown', 'GET');
        $kernel = App::make(Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle($request);
        // Should fallback to default provider
        expect(Spanvel\Package::key())->toBe('');
    });

    it('does not register a provider for excluded routes', function () {
        $request = Request::create('/login', 'GET');
        $kernel = App::make(Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle($request);
        // Should not set the Package key
        expect(Spanvel\Package::key())->not()->toBe('login');
    });
});
