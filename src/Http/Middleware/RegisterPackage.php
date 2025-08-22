<?php

namespace Spanvel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spanvel\Support\Facades\Package;
use Symfony\Component\HttpFoundation\Response;

class RegisterPackage
{
    /**
     * Dynamically registers a package service provider based on the first URI segment.
     *
     * - Skips when the segment is excluded.
     * - Registers the matching provider for a direct segment match.
     * - Falls back to the root provider (key: '') if no match is found.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $segment = (string) $request->segment(1);
        $providers = config('packages.providers', []);
        $excluded = config('packages.excluded_segments', []);

        if ($segment !== '' && in_array($segment, $excluded, true)) {
            return $next($request);
        }

        $provider = $providers[$segment] ?? ($providers[''] ?? null);

        if ($provider) {
            if ($segment !== '' && isset($providers[$segment])) {
                Package::setKey($segment);
            }

            app()->register($provider);
        }

        return $next($request);
    }
}
