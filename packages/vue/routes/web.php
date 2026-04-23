<?php

use Illuminate\Support\Facades\Route;

Route::view('{any?}', 'vue-package-dev::app')
    ->where('any', '^(?!api).*$');
