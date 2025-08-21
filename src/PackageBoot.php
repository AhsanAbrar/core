<?php

namespace Spanvel;

use Illuminate\Support\Facades\Route;

class PackageBoot
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
            $this->basePath . DIRECTORY_SEPARATOR . 'resources/views'
        );

        return $this;
    }

    /**
     * Register web routes for the package.
     */
    public function webRoutes(
        string|array|null $middleware = null,
        ?string $prefix = null,
        ?string $domain = null,
        ?string $controller = null
    ): static {
        return $this->routes(
            'web.php',
            $middleware ?? 'web',
            $prefix ?? Package::key(),
            $domain,
            $controller,
        );
    }

    /**
     * Register API routes for the package.
     */
    public function apiRoutes(
        string|array|null $middleware = null,
        ?string $prefix = null,
        ?string $domain = null,
        ?string $controller = null
    ): static {
        return $this->routes(
            'api.php',
            $middleware ?? 'api',
            $prefix ?? Package::key() . '/api',
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
            'prefix'     => $prefix,
            'domain'     => $domain,
            'controller' => $controller,
        ], fn ($value) => $value !== null);
    }

    /**
     * Determine the full filesystem path to a route file.
     */
    protected function getRoutePath(string $filename): string
    {
        return $this->basePath
            . '/routes/'
            . ltrim($filename, '/\\');
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
