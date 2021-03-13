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

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{slug}', [PostController::class, 'show']);
Route::post('posts/search', [PostController::class, 'search']);

// Route::group(['as' => 'api.'], function() {
//     Orion::resource('posts', PostController::class);
// });
