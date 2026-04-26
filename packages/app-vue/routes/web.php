<?php

use Illuminate\Support\Facades\Route;

Route::view('{any?}', 'app-vue-package::app')
    ->where('any', '^(?!api).*$');
