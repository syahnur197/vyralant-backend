<?php

use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;


Route::get('user', [UserController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('user.index');

Route::post('auth/logout', LogoutController::class)
    ->middleware('auth:sanctum')
    ->name('logout');


Route::group(['prefix' => 'posts'], function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('{slug}', [PostController::class, 'show']);
    Route::post('search', [PostController::class, 'search']);
    Route::post('/', [PostController::class, 'store'])->middleware('auth:sanctum');
});
