<?php

use Illuminate\Support\Facades\Route;
use [[rootNamespace]]\Http\Controllers\Api\ProfileController;
use [[rootNamespace]]\Http\Controllers\Api\SettingsEmailController;
use [[rootNamespace]]\Http\Controllers\Api\SettingsGeneralController;
use [[rootNamespace]]\Http\Controllers\Api\UserController;

Route::resource('users', UserController::class);

Route::resource('profile', ProfileController::class)->only(['create', 'store']);

Route::prefix('settings')->group(function () {
    Route::resource('general', SettingsGeneralController::class)->only(['create', 'store']);
    Route::resource('email', SettingsEmailController::class)->only(['create', 'store']);
});
