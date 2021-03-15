<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'posts'], function() {
    Route::get('/', [PostController::class, 'index']);
    Route::get('{slug}', [PostController::class, 'show']);
    Route::post('search', [PostController::class, 'search']);
    Route::post('/', [PostController::class, 'store']);
});


// Route::group(['as' => 'api.'], function() {
//     Orion::resource('posts', PostController::class);
// });
