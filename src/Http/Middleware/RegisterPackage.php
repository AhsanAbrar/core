<?php

namespace Spanvel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spanvel\Support\Facades\Package;

class RegisterPackage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        [$providers, $excluded] = [
            (array) config('packages.providers', []),
            (array) config('packages.excluded_segments', config('packages.excluded_routes', [])),
        ];

        // Normalize first segment (null => ''), lowercase for safer matching.
        $segment = Str::lower((string) ($request->segment(1) ?? ''));
        $segment = $segment === null ? '' : $segment;

        // If excluded (e.g. login, register), skip all package registration.
        if ($segment !== '' && in_array($segment, $excluded, true)) {
            return $next($request);
        }

        // Always register the default/root provider if configured.
        // This allows the root package to serve its own routes like /foo, /bar, etc.
        if (array_key_exists('', $providers)) {
            $this->registerOnce($providers['']);
        }

        // If first segment maps to a specific provider, register it too.
        if ($segment !== '' && array_key_exists($segment, $providers)) {
            Package::setKey($segment);
            $this->registerOnce($providers[$segment]);
        } else {
            // Otherwise, treat as root.
            Package::setKey('');
        }

        return $next($request);
    }

    /**
     * Register the given provider if it isn't already loaded.
     */
    protected function registerOnce(string $provider): void
    {
        $app = app();

        // Laravel keeps a list of loaded providers we can check.
        $loaded = method_exists($app, 'getLoadedProviders')
            ? $app->getLoadedProviders()
            : [];

        if (! isset($loaded[$provider]) || $loaded[$provider] !== true) {
            $app->register($provider);
        }
    }
}
