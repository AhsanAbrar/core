<?php

namespace Spanvel\Package;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;

class Bootstrapper
{
    /**
     * Absolute base path of the package.
     */
    public function __construct(
        protected string $basePath,
        protected ?string $key = null,
    ) {
        $this->basePath = dirname(rtrim($basePath, '/\\'));
    }

    /**
     * Register a view namespace for this package.
     */
    public function views(?string $namespace = null, string $relative = 'resources/views'): static
    {
        $path = $this->basePath.DIRECTORY_SEPARATOR.ltrim($relative, '/\\');

        $namespace ??= $this->key;

        if ($namespace === null) {
            throw new \InvalidArgumentException(sprintf(
                "Unable to register views for package at [%s]: no namespace provided and no key set.\n".
                "Hint: call ->views('your-namespace') or set a package key in Package::boot(__DIR__, key: 'your-key').",
                $path
            ));
        }

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
            $name,
            $domain
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
            $name,
            $domain
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
            $name,
            $domain
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
            'prefix' => $this->composePrefix($prefix),
            'domain' => $domain,
            'as' => $this->composeName($name),
        ];

        return array_filter($group, static fn ($value) => $value !== null);
    }

    protected function composePrefix(?string $prefix): ?string
    {
        $parts = [];

        if ($this->key) {
            $parts[] = trim($this->key, '/');
        }

        if ($prefix) {
            $parts[] = trim($prefix, '/');
        }

        return $parts ? implode('/', $parts) : null;
    }

    protected function composeName(?string $name): ?string
    {
        if (! $this->key) {
            return $name;
        }

        if (! $name) {
            return $this->key.'.';
        }

        return str_starts_with($name, $this->key.'.')
            ? $name
            : $this->key.'.'.$name;
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
