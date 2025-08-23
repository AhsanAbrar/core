<?php

namespace [[rootNamespace]];

use Illuminate\Support\Facades\Blade;

class BladeComponents
{
    /**
     * Register the Blade components.
     */
    public static function register()
    {
        Blade::component('layout', \[[rootNamespace]]\View\Components\Layout::class);
    }
}
