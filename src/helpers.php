<?php

use AhsanDev\Support\Vite;

if (! function_exists('option')) {
    /**
     * Get or set the specified option value(s).
     *
     * Usage:
     * option('site_name');                   // get
     * option('site_name', 'Default');        // get with default
     * option(['site_name' => 'MyApp']);      // set multiple
     * option('site_name', 'MyApp', true);    // set single (optional improvement)
     *
     * @return mixed|\Spanvel\Support\Contracts\Option
     */
    function option(string|array|null $key = null, mixed $default = null): mixed
    {
        $service = app('option');

        if (is_null($key)) {
            return $service;
        }

        if (is_array($key)) {
            return $service->put($key);
        }

        return $service->get($key, $default);
    }
}

if (! function_exists('vite_tags')) {
    /**
     * Get the path to a versioned vite file.
     *
     * @param  string  $manifestDirectory
     * @param  int  $port
     * @param  string|null  $file
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    function vite_tags($manifestDirectory = '', $port = 5173, $file = 'main.js')
    {
        return app(Vite::class)($manifestDirectory, $port, $file);
    }
}
