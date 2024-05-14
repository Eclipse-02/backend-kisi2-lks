<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::controller(UserController::class)->group(function() {
    Route::get('/', 'index')->middleware('auth:sanctum');
    Route::get('/{username}', 'show')->middleware('auth:sanctum');
});