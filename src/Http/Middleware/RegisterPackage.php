<?php

namespace Spanvel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spanvel\Support\Facades\Package;

class RegisterPackage
{
    /**
     * Resolve the package key from the first URI segment and
     * register the appropriate service provider for this request.
     *
     * Rules:
     * - If segment is in `packages.excluded_segments`: set key to '' and register nothing.
     * - If segment is empty or unknown: set key to '' and register the root provider (if configured).
     * - If segment matches a configured provider: set key to that segment and register its provider.
     */
    public function handle(Request $request, Closure $next)
    {
        $providers = (array) config('packages.providers', []);
        $excluded  = (array) config('packages.excluded_segments', []);

        $segment = (string) ($request->segment(1) ?? '');

        // Excluded segments: do not register any provider; key remains root ('')
        if ($segment !== '' && in_array($segment, $excluded, true)) {
            Package::key(''); // explicit for clarity
            return $next($request);
        }

        // Known segment → use that provider; otherwise fall back to root ('')
        $key = ($segment !== '' && array_key_exists($segment, $providers)) ? $segment : '';

        // Persist per-request key (facade -> scoped context)
        Package::key($key);

        // Register the appropriate provider (if any), guarding against double-registration
        if ($key === '') {
            if (isset($providers['']) && ! app()->providerIsLoaded($providers[''])) {
                app()->register($providers['']);
            }
        } else {
            $provider = $providers[$key] ?? null;

            if ($provider && ! app()->providerIsLoaded($provider)) {
                app()->register($provider);
            }
        }

        return $next($request);
    }
}
