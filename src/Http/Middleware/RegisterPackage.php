<?php

namespace Spanvel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spanvel\Support\Facades\Package;

class RegisterPackage
{
    /**
     * Cached per-request config.
     */
    protected array $providers;

    protected array $excluded;

    public function __construct()
    {
        $this->providers = (array) config('packages.providers', []);
        $this->excluded = (array) config('packages.excluded_segments', []);
    }

    /**
     * Resolve the active package key and register its provider (if any).
     */
    public function handle(Request $request, Closure $next)
    {
        $segment = $this->segment($request);

        // Excluded: keep root context and register nothing.
        if ($this->isExcluded($segment)) {
            Package::key('');

            return $next($request);
        }

        $key = $this->keyFor($segment);

        Package::key($key);

        if ($provider = $this->providerFor($key)) {
            $this->registerOnce($provider);
        }

        return $next($request);
    }

    /**
     * First URI segment as string.
     */
    protected function segment(Request $request): string
    {
        return (string) ($request->segment(1) ?? '');
    }

    /**
     * Whether the segment is excluded from package resolution.
     */
    protected function isExcluded(string $segment): bool
    {
        return $segment !== '' && in_array($segment, $this->excluded, true);
    }

    /**
     * Decide which key to use:
     * - known non-empty segment => that segment
     * - empty or unknown        => root ('')
     */
    protected function keyFor(string $segment): string
    {
        return ($segment !== '' && array_key_exists($segment, $this->providers))
            ? $segment
            : '';
    }

    /**
     * Map key to provider class, handling root fallback.
     */
    protected function providerFor(string $key): ?string
    {
        return $this->providers[$key]
            ?? ($key === '' ? ($this->providers[''] ?? null) : null);
    }

    /**
     * Register a provider once for this request.
     */
    protected function registerOnce(string $provider): void
    {
        if (! app()->providerIsLoaded($provider)) {
            app()->register($provider);
        }
    }
}
