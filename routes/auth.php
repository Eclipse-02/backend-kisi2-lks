<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register')->middleware('guest');
    Route::post('login', 'login')->middleware('guest');
    Route::post("logout", 'logout')->middleware('auth:sanctum');
    Route::get("me", 'me')->middleware('auth:sanctum');
});