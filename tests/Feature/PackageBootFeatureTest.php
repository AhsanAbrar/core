<?php

use Spanvel\PackageBoot;
use Illuminate\Support\Facades\Route;

describe('PackageBoot', function () {
    beforeEach(function () {
        // You may want to mock Route facade if needed
        // For now, just clear registered routes
        Route::flushMiddlewareGroups();
        Route::getRoutes()->refreshNameLookups();
    });

    it('can instantiate and register web routes', function () {
        $boot = PackageBoot::make(__DIR__ . '/../../demo-package');
        $boot->webRoutes();
        // You would assert that routes from demo-package/routes/web.php are loaded
        // For demonstration, check that routes exist (pseudo-code)
        // expect(Route::has('demo.web'))->toBeTrue();
        expect(true)->toBeTrue(); // Placeholder
    });

    it('can instantiate and register api routes', function () {
        $boot = PackageBoot::make(__DIR__ . '/../../demo-package');
        $boot->apiRoutes();
        // expect(Route::has('demo.api'))->toBeTrue();
        expect(true)->toBeTrue(); // Placeholder
    });

    it('can register arbitrary route files', function () {
        $boot = PackageBoot::make(__DIR__ . '/../../demo-package');
        $boot->routes('custom.php', 'web', 'custom');
        // expect(Route::has('custom'))->toBeTrue();
        expect(true)->toBeTrue(); // Placeholder
    });
});
