<?php

use Illuminate\Support\Facades\Route;

Route::view('{any?}', '[[name]]::app')
    ->where('any', '^(?!api).*$');
