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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
     *
     * @param  string  $segment
     * @return string|null
     */
    protected function resolveProvider(string $segment): ?string
    {
        $providers = (array) config('packages.providers', []);

        if ($this->isExcluded($segment)) {
            return null;
        }

        if ($segment !== '' && array_key_exists($segment, $providers)) {
            Package::setKey($segment);

            return $providers[$segment];
        }

        return $providers[''] ?? null;
    }

    protected function isExcluded(string $segment): bool
    {
        $excluded = (array) config('packages.excluded_segments', []);

        return $segment !== '' && in_array($segment, $excluded, true);
    }
}
