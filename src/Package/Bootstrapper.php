<?php

namespace Spanvel\Package;

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
     * Instantiate the class fluently.
     */
    public static function make(string $packagePath): static
    {
        return new static($packagePath);
    }

    /**
     * Prepend the package view path to the view finder.
     */
    public function views(): static
    {
        app('view')->getFinder()->prependLocation(
            $this->basePath.DIRECTORY_SEPARATOR.'resources/views'
        );

        return $this;
    }

    /**
     * Register web routes for the package.
     */
    public function webRoutes(
        string $filename = 'web.php',
        string|array|null $middleware = null,
        ?string $prefix = null,
        ?string $domain = null,
        ?string $controller = null
    ): static {
        return $this->routes(
            $filename,
            $this->mergeMiddleware(['web'], $middleware),
            $prefix ?? Package::key(),
            $domain,
            $controller,
        );
    }

    /**
     * Register API routes for the package.
     */
    public function apiRoutes(
        string $filename = 'api.php',
        string|array|null $middleware = null,
        ?string $prefix = null,
        ?string $domain = null,
        ?string $controller = null
    ): static {
        return $this->routes(
            $filename,
            $this->mergeMiddleware(['api'], $middleware),
            $prefix ?? Package::key().'/api',
            $domain,
            $controller,
        );
    }

    /**
     * Register sanctum api routes for the package.
     */
    public function sanctumRoutes(
        string $filename = 'api.php',
        bool $stateful = true,
        string|array|null $middleware = null,
        ?string $prefix = null,
        ?string $domain = null,
        ?string $controller = null
    ): static {
        $defaults = $stateful
            ? [\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, 'auth:sanctum', 'api']
            : ['auth:sanctum', 'api'];

        return $this->routes(
            $filename,
            $this->mergeMiddleware($defaults, $middleware),
            $prefix ?? Package::key().'/api',
            $domain,
            $controller,
        );
    }

    /**
     * Register routes from an arbitrary file.
     */
    public function routes(
        string $filename,
        string|array|null $middleware = null,
        ?string $prefix = null,
        ?string $domain = null,
        ?string $controller = null
    ): static {
        $group = $this->buildGroup($middleware, $prefix, $domain, $controller);

        return $this->loadRoutes($filename, $group);
    }

    /**
     * Exclude the given route segments globally.
     *
     * The provided segments will be merged with any existing values defined in
     * the `packages.excluded_segments` configuration key. Duplicates are
     * automatically removed and the array is re-indexed.
     */
    public function excludeSegments(array $segments): static
    {
        $existing = app('config')->get('packages.excluded_segments', []);

        $merged = array_values(array_unique(array_merge($existing, $segments)));

        app('config')->set('packages.excluded_segments', $merged);

        return $this;
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
        ?string $controller
    ): array {
        return array_filter([
            'middleware' => $middleware,
            'prefix' => $prefix,
            'domain' => $domain,
            'controller' => $controller,
        ], fn ($value) => $value !== null);
    }

    /**
     * Determine the full filesystem path to a route file.
     */
    protected function getRoutePath(string $filename): string
    {
        return $this->basePath
            .'/routes/'
            .ltrim($filename, '/\\');
    }

    /**
     * Load and (optionally) group the given route file.
     */
    protected function loadRoutes(string $filename, array $group): static
    {
        $path = $this->getRoutePath($filename);

        if (! is_file($path)) {
            return $this;
        }

        $loader = fn () => require $path;

        empty($group)
            ? $loader()
            : Route::group($group, $loader);

        return $this;
    }
}
