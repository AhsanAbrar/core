<?php

namespace Spanvel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spanvel\Support\Facades\Package;

class RegisterPackage
{
    /**
     * Resolve the active package key for this request and register its provider.
     */
    public function handle(Request $request, Closure $next)
    {
        $segment = $this->firstSegment($request);

        if ($this->isExcluded($segment)) {
            Package::key(''); // stay at root, register nothing

            return $next($request);
        }

        $key = $this->resolveKey($segment);

        Package::key($key);

        $this->registerForKey($key);

        return $next($request);
    }

    /**
     * Get the first URI segment as a normalized string.
     */
    protected function firstSegment(Request $request): string
    {
        return (string) ($request->segment(1) ?? '');
    }

    /**
     * Determine if the segment is excluded from package resolution.
     */
    protected function isExcluded(string $segment): bool
    {
        if ($segment === '') {
            return false;
        }

        return in_array($segment, $this->excludedSegments(), true);
    }

    /**
     * Decide which key to use based on configured providers.
     * - known non-empty segment → that segment
     * - empty or unknown → root ('')
     */
    protected function resolveKey(string $segment): string
    {
        if ($segment === '') {
            return '';
        }

        return array_key_exists($segment, $this->providers()) ? $segment : '';
    }

    /**
     * Register the provider for the resolved key (if any), guarding against duplicates.
     */
    protected function registerForKey(string $key): void
    {
        $providers = $this->providers();

        $provider = $key === ''
            ? ($providers[''] ?? null)
            : ($providers[$key] ?? null);

        $this->maybeRegister($provider);
    }

    /**
     * Register a provider class if present and not yet loaded.
     */
    protected function maybeRegister(?string $provider): void
    {
        if (! $provider) {
            return;
        }

        if (! app()->providerIsLoaded($provider)) {
            app()->register($provider);
        }
    }

    /**
     * Config: map of segment => provider class.
     */
    protected function providers(): array
    {
        return (array) config('packages.providers', []);
    }

    /**
     * Config: excluded segments (no registration; key stays root).
     */
    protected function excludedSegments(): array
    {
        return (array) config('packages.excluded_segments', []);
    }
}
