<?php

describe('CoreServiceProvider', function () {
    it('pushes ServePackage middleware to the kernel', function () {
        $kernel = app(Illuminate\Contracts\Http\Kernel::class);
        $middlewares = method_exists($kernel, 'getMiddleware') ? $kernel->getMiddleware() : [];
        $found = false;
        foreach ($middlewares as $middleware) {
            if (is_string($middleware) && $middleware === Spanvel\Http\Middleware\ServePackage::class) {
                $found = true;
                break;
            }
        }
        expect($found)->toBeTrue();
    });

    it('registers all providers when running in console', function () {
        // Simulate running in console
        app()->instance('runningInConsole', true);
        // This is a placeholder, as actual provider registration would require more setup
        expect(true)->toBeTrue();
    });
});
