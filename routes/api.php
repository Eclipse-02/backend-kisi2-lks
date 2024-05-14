<?php

use App\Http\Controllers\FollowingController;
use App\Http\Controllers\FollowerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function() {
    require(__DIR__ . '/auth.php');
});

Route::prefix('posts')->group(function() {
    require(__DIR__ . '/post.php');
});

Route::controller(FollowingController::class)->group(function() {;
    Route::post("/users/{username}/follow", 'store')->middleware('auth:sanctum');
    Route::post("/users/{username}/unfollow", 'destroy')->middleware('auth:sanctum');
    Route::get("/users/following", 'index')->middleware('auth:sanctum');
});

// Route::get('/users/me', [AuthController::class, 'index']);

Route::controller(FollowerController::class)->group(function() {;
    Route::post("/users/{username}/accept", 'store')->middleware('auth:sanctum');
    Route::get("/users/{username}/followers", 'index')->middleware('auth:sanctum');
});

Route::prefix('users')->group(function() {
    require(__DIR__ . '/user.php');
});