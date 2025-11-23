<?php

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
