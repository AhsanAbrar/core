<?php

namespace Auth;

use Illuminate\Support\Facades\Blade;

class BladeComponents
{
    /**
     * Register the Blade components.
     */
    public static function register()
    {
        Blade::component('auth-layout', \Auth\View\Components\AuthLayout::class);
    }
}
