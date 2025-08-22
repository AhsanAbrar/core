<?php

namespace Spanvel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spanvel\Support\Facades\Package;

class ServePackage
{
    public function handle(Request $request, Closure $next): Response
    {
        $segment   = (string) $request->segment(1);
        $providers = config('packages.providers', []);
        $excluded  = config('packages.excluded_segments', []);

        // Short-circuit: skip dynamic registration if excluded
        if ($segment !== '' && in_array($segment, $excluded, true)) {
            return $next($request);
        }

        // Resolve provider: direct match or fallback ('')
        $provider = $providers[$segment] ?? ($providers[''] ?? null);

        if ($provider) {
            Package::setKey($segment);

            app()->register($provider);
        }

        return $next($request);
    }
}
