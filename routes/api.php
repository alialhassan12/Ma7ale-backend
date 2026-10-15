<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login',[AuthController::class,'login'])->name('email.login');

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/me',[AuthController::class,'checkAuth'])->name('me');
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
});