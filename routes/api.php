<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Post\PostController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Post\PostCommentController;
use App\Http\Controllers\Api\Post\VoteController;

Route::get('user', [UserController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('user.index');

Route::post('auth/logout', LogoutController::class)
    ->middleware('auth:sanctum')
    ->name('logout');


Route::group(['prefix' => 'posts'], function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('{slug}', [PostController::class, 'show']);
    Route::get('{slug}/comments', [PostCommentController::class, 'index']);
    Route::post('{post}/comments', [PostCommentController::class, 'store']);
    Route::post('{slug}/upvote', [VoteController::class, 'upvote'])->middleware('auth:sanctum');
    Route::post('{slug}/downvote', [VoteController::class, 'downvote'])->middleware('auth:sanctum');
    Route::post('search', [PostController::class, 'search']);
    Route::post('/', [PostController::class, 'store'])->middleware('auth:sanctum');
});
