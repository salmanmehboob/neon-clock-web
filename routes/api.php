<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CategoryImageController;
use App\Http\Controllers\Api\WallpaperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Route::middleware('auth:sanctum')->group(function () {
// Categories
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

// Category Images
Route::get('category-images', [CategoryImageController::class, 'index']);
Route::get('category-images/{slug}', [CategoryImageController::class, 'show']);
Route::get('categories/{slug}/images', [CategoryImageController::class, 'getImagesByCategory']);

// Wallpapers
Route::get('wallpapers', [WallpaperController::class, 'index']);
Route::get('wallpapers/{id}', [WallpaperController::class, 'show']);
//});