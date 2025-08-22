<?php

namespace Spanvel\Http\Middleware;

use Spanvel\Support\Facades\Package;

class ServePackageNewOld
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
        $this->segmentOne = $request->segment(1) ?? '';
    }

    /**
     * Determine if the given request is intended for Package.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function isPackageRequest($request)
    {
        if (in_array($this->segmentOne, config('packages.excluded_segments'))) {
            return false;
        }

        $key = $this->segmentOne;
        $providers = config('packages.providers');

        if (array_key_exists($key, $providers)) {
            Package::setKey($key);

            return $providers[$key];
        } elseif (array_key_exists('', $providers)) {
            return $providers[''];
        }
    }
}
