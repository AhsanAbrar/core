<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => 'site-web-root');
Route::get('/hello', fn () => view('hello'));
