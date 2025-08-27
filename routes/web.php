<?php

use App\Http\Controllers\ApiTesterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryImageController;
use App\Http\Controllers\WallpaperController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

 
Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

 

Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('wallpapers', WallpaperController::class);

    Route::resource('category-images', CategoryImageController::class);

});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('apis', [ApiTesterController::class, 'index'])->name('apis.index');
    Route::post('apis/call', [ApiTesterController::class, 'call'])->name('apis.call');
    Route::post('apis/token', [ApiTesterController::class, 'createToken'])->name('apis.token'); // create personal access token
});