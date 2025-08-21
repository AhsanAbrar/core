<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Package Service Providers
    |--------------------------------------------------------------------------
    |
    | Map the first URL segment to a service provider. When a request comes in,
    | the middleware will attempt to match segment(1) to a key in this array
    | and register the corresponding provider for that request lifecycle.
    |
    | Use an empty string ('') as the key to define a default (root) provider
    | that should be registered when there is no first segment or when no key
    | matches. Example:
    |
    | ''      => Site\SiteServiceProvider::class,
    | 'admin' => Admin\AdminServiceProvider::class,
    |
    */

    'providers' => [
        // ''      => Site\SiteServiceProvider::class,
        // 'admin' => Admin\AdminServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Excluded Segments
    |--------------------------------------------------------------------------
    |
    | First-segment values that should never trigger package registration.
    | This prevents conflicts with framework/auth routes that live in
    | the default application (e.g., /login, /register, etc.).
    |
    */

    'excluded_segments' => [
        'login',
        'register',
        'forgot-password',
        'reset-password',
        'verify-email',
        'email',
        'confirm-password',
        'logout',
    ],

];
