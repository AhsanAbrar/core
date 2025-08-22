<?php

namespace Spanvel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ServePackageNew
{
    public function handle(Request $request, Closure $next)
    {
        // Given
        // first segment of the request
        // or first segment null
        $segment = (string) $request->segment(1);
        dd($segment);

        return $next($request);
    }
}
