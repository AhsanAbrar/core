<?php

namespace Spanvel\Http\Middleware;

use Spanvel\Package;

class ServePackage
{
    /**
     * The request segment 1.
     *
     * @var string
     */
    protected $segmentOne;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        $this->setSegments($request);

        if ($provider = $this->isPackageRequest($request)) {
            app()->register($provider);
        }

        return $next($request);
    }

    /**
     * Set segments for livewire and default request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function setSegments($request)
    {
        // i have to refactor this whole block.
        // waiting for better idea.

        // if ($request->hasHeader('X-Livewire')) {
        // $path = explode('/', $request->fingerprint['path']);
        // $this->segmentOne = $path[0] ?? null;
        // $this->segmentTwo = $path[1] ?? null;
        // return;
        // }

        $this->segmentOne = $request->segment(1);
    }

    /**
     * Determine if the given request is intended for Package.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function isPackageRequest($request)
    {
        // i have to refactor this whole block.
        // waiting for better idea.

        if (in_array($this->segmentOne, config('packages.excluded_routes'))) {
            return false;
        }

        $key = $this->segmentOne;
        $providers = config('packages.providers');

        if (array_key_exists($key, $providers)) {
            Package::key($key);

            return $providers[$key];
        } elseif (array_key_exists('', $providers)) {
            return $providers[''];
        }
    }
}
