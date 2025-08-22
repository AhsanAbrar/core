<?php

use Illuminate\Support\Facades\Route;

// This relies on Route::group(['controller' => DemoController::class]) support.
Route::get('/', 'index');
Route::get('/about', 'about');
