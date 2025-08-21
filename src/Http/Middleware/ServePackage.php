<?php

namespace Spanvel\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spanvel\Span;

class ServePackage
{
    /**
     * The request segment 1.
     *
     * @var string
     */
    protected $segmentOne;

    /**
     * The request segment 2.
     *
     * @var string
     */
    protected $segmentTwo;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        // Get all routes
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'uri' => $route->uri(),        // e.g. "posts/{id}/edit"
                'method' => $route->methods(),    // e.g. ["GET", "HEAD"]
                'name' => $route->getName(),    // e.g. "posts.edit"
                'action' => $route->getActionName(), // e.g. "App\Http\Controllers\PostController@edit"
            ];
        })->toArray();

        $uris = collect(Route::getRoutes())
            ->map(fn ($route) => $route->uri())
            ->toArray();

        $firstSegments = collect(Route::getRoutes())
            ->map(fn ($route) => ltrim($route->uri(), '/'))
            ->filter()
            ->map(fn ($uri) => Str::before($uri, '/'))
            ->unique()
            ->values()
            ->all();

        // Example dump
        dd($firstSegments);
        dd($uris);
        dd($routes);

        $this->setSegments($request);

        if ($provider = $this->isSpanRequest($request)) {
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
        $this->segmentTwo = $request->segment(2);
    }

    /**
     * Determine if the given request is intended for Span.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function isSpanRequest($request)
    {
        // i have to refactor this whole block.
        // waiting for better idea.

        if (in_array($this->segmentOne, config('span.excluded_routes'))) {
            return false;
        }

        $hasPrefix = $this->segmentOne == config('span.prefix');
        $key = $hasPrefix ? $this->segmentTwo : $this->segmentOne;
        $providers = $hasPrefix
            ? config('span.prefix_providers')
            : config('span.providers');

        if (array_key_exists($key, $providers)) {
            Span::key($key);
            Span::prefix($hasPrefix ? config('span.prefix').'/'.$key : $key);

            return $providers[$key];
        } elseif (array_key_exists('', $providers)) {
            Span::prefix($hasPrefix ? config('span.prefix') : '');

            return $providers[''];
        }
    }
}
