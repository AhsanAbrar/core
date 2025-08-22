<?php

namespace Spanvel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spanvel\Support\Facades\Package;
use Symfony\Component\HttpFoundation\Response;

class RegisterPackage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($provider = $this->resolveProvider((string) $request->segment(1))) {
            app()->register($provider);
        }

        return $next($request);
    }

    /**
     * Resolve a provider for the given URI segment.
     */
    protected function resolveProvider(string $segment): ?string
    {
        if ($this->isExcluded($segment)) {
            return null;
        }

        $providers = $this->providers();

        return $this->directProvider($segment, $providers)
            ?? $this->fallbackProvider($providers);
    }

    protected function directProvider(string $segment, array $providers): ?string
    {
        if ($segment !== '' && isset($providers[$segment])) {
            Package::setKey($segment);

            return $providers[$segment];
        }

        return null;
    }

    protected function fallbackProvider(array $providers): ?string
    {
        return $providers[''] ?? null;
    }

    protected function isExcluded(string $segment): bool
    {
        return $segment !== '' && in_array($segment, $this->excludedSegments(), true);
    }

    protected function providers(): array
    {
        return (array) config('packages.providers', []);
    }

    protected function excludedSegments(): array
    {
        return (array) config('packages.excluded_segments', []);
    }
}
