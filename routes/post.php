<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::controller(PostController::class)->group(function() {;
    Route::post("/", 'store')->middleware('auth:sanctum');
    Route::post("/{post}", 'destroy')->middleware('auth:sanctum');
    Route::get("/", 'show')->middleware('auth:sanctum');
});