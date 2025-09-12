<?php

namespace Spanvel\Package;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;

class Bootstrapper
{
    /**
     * Absolute base path of the package.
     */
    public function __construct(private string $basePath)
    {
        $this->basePath = dirname(rtrim($basePath, '/\\'));
    }

    /**
     * Register a view namespace for this package.
     *
     * Usage: ->views('blog') then view('blog::home')
     */
    public function views(string $namespace, string $relative = 'resources/views'): static
    {
        $path = $this->basePath.DIRECTORY_SEPARATOR.ltrim($relative, '/\\');

        app('view')->addNamespace($namespace, $path);

        return $this;
    }

    /**
     * Register web routes for the package.
     */
    public function webRoutes(
        string $filename = 'web.php',
        string|array|null $middleware = null,
        ?string $prefix = null,
        ?string $name = null,
        ?string $domain = null
    ): static {
        return $this->routes(
            $filename,
            $this->mergeMiddleware(['web'], $middleware),
            $prefix,
            $domain,
            $name
        );
    }

    /**
     * Register API routes for the package.
     */
    public function apiRoutes(
        string $filename = 'api.php',
        string|array|null $middleware = null,
        ?string $prefix = 'api',
        ?string $name = null,
        ?string $domain = null
    ): static {
        return $this->routes(
            $filename,
            $this->mergeMiddleware(['api'], $middleware),
            $prefix,
            $domain,
            $name
        );
    }

    /**
     * Register sanctum api routes for the package.
     */
    public function sanctumRoutes(
        string $filename = 'api.php',
        bool $stateful = true,
        string|array|null $middleware = null,
        ?string $prefix = 'api',
        ?string $name = null,
        ?string $domain = null
    ): static {
        $defaults = $stateful
            ? [\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, 'auth:sanctum', 'api']
            : ['auth:sanctum', 'api'];

        return $this->routes(
            $filename,
            $this->mergeMiddleware($defaults, $middleware),
            $prefix,
            $domain,
            $name
        );
    }

    /**
     * Register console command routes for the package.
     */
    public function commandRoutes(string $filename = 'console.php'): static
    {
        if (app()->runningInConsole()) {
            return $this->routes($filename);
        }

        return $this;
    }

    /**
     * Generic loader for a routes file with optional group attributes.
     */
    public function routes(
        string $filename,
        string|array|null $middleware = null,
        ?string $prefix = null,
        ?string $name = null,
        ?string $domain = null
    ): static {
        $group = $this->buildGroup($middleware, $prefix, $domain, $name);

        return $this->loadRoutes($filename, $group);
    }

    /**
     * Merge default middleware with user-provided ones.
     *
     * If $custom is null → return only defaults.
     * If $custom is []   → treat as override (no defaults).
     * Otherwise          → merge + dedupe.
     */
    protected function mergeMiddleware(array $defaults, string|array|null $custom): array
    {
        if ($custom === null) {
            return $defaults;
        }

        if ($custom === []) {
            return [];
        }

        $merged = array_merge($defaults, (array) $custom);

        return array_values(array_unique($merged, SORT_REGULAR));
    }

    /**
     * Build the attributes array for Route::group().
     */
    protected function buildGroup(
        string|array|null $middleware,
        ?string $prefix,
        ?string $domain,
        ?string $name
    ): array {
        $group = [
            'middleware' => $middleware,
            'prefix' => $prefix,
            'domain' => $domain,
            'as' => $name,
        ];

        return array_filter($group, static fn ($value) => $value !== null);
    }

    /**
     * Determine the full filesystem path to a route file.
     */
    protected function getRoutePath(string $filename): string
    {
        return $this->basePath.'/routes/'.ltrim($filename, '/\\');
    }

    /**
     * Determine if the application routes are cached.
     */
    protected function routesAreCached(): bool
    {
        $app = app();

        return $app instanceof CachesRoutes && $app->routesAreCached();
    }

    /**
     * Load the given route file and apply an optional Route::group().
     */
    protected function loadRoutes(string $filename, array $group = []): static
    {
        if ($this->routesAreCached()) {
            return $this;
        }

        $path = $this->getRoutePath($filename);

        if (empty($group)) {
            require $path;
        } else {
            Route::group($group, fn () => require $path);
        }

        return $this;
    }
}
