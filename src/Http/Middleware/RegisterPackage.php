<?php

namespace Spanvel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spanvel\Support\Facades\Package;
use Symfony\Component\HttpFoundation\Response;

class RegisterPackage
{
    /** Register a package provider (exclusion + direct match + fallback) using only one `if` here. */
    public function handle(Request $request, Closure $next): Response
    {
        $segment = (string) $request->segment(1);

        if ($provider = $this->resolveProvider($segment)) {
            app()->register($provider);
        }

        return $next($request);
    }

    /**
     * Resolve the provider for the given segment.
     * - Returns null if excluded or no provider found.
     * - Sets Package key on direct hit.
     */
    protected function resolveProvider(string $segment): ?string
    {
        $providers = (array) config('packages.providers', []);
        $excluded  = (array) config('packages.excluded_segments', []);

        // Exclusion rule
        if ($segment !== '' && in_array($segment, $excluded, true)) {
            return null;
        }

        // Direct hit
        if ($segment !== '' && array_key_exists($segment, $providers)) {
            Package::setKey($segment);

            return $providers[$segment];
        }

        // Fallback
        return $providers[''] ?? null;
    }
}
