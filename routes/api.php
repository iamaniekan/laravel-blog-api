<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{slug}', [PostController::class, 'show']);
Route::get('posts/get/{slug}',  [PostController::class, 'getPost']);
Route::get('posts/related/{slug}',  [PostController::class, 'getRelatedPosts']);
Route::get('category/{slug}/posts', [CategoryController::class, 'getPostsByCategory']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{slug}', [PostController::class, 'update']);
    Route::delete('posts/{slug}', [PostController::class, 'destroy']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{slug}', [CategoryController::class, 'update']);
    Route::delete('categories/{slug}', [CategoryController::class, 'destroy']);
});
