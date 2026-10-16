<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register',[AuthController::class,'register'])->name('register');
Route::post('/auth/login',[AuthController::class,'login'])->name('email.login');

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/auth/me',[AuthController::class,'checkAuth'])->name('me');
    Route::post('/auth/logout',[AuthController::class,'logout'])->name('logout');
});