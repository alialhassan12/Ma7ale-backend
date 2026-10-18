<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusinessCategoriesController;
use App\Http\Controllers\Api\BusinessController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OAuthController;

Route::post('/auth/register',[AuthController::class,'register'])->name('register');
Route::post('/auth/login',[AuthController::class,'login'])->name('email.login');

Route::prefix('auth/google')->group(function(){
    Route::get('/redirect', [OAuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/callback', [OAuthController::class, 'callbackToGoogle'])->name('google.callback');
});

// TODO:admin routes
Route::prefix('admin')->group(function(){
    Route::post('/add-business-category',[BusinessCategoriesController::class,'addBusinessCategory'])->name('add-business-category');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/auth/me',[AuthController::class,'checkAuth'])->name('me');
    Route::post('/auth/logout',[AuthController::class,'logout'])->name('logout');
    
});
Route::post('/business/create',[BusinessController::class,'createBusiness'])->name('create.business');