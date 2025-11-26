<?php

use Illuminate\Support\Facades\Route;

Route::view('{any?}', 'vue-example::app')
    ->where('any', '^(?!api).*$');
