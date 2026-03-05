<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;


Route::post('/login',[AuthController::class, 'authenticate']);

Route::middleware(['auth:web'])->group(function () {
    Route::get('/auth',[AuthController::class, 'fetchAuth']);
    Route::post('/logout',[AuthController::class, 'logout']);

    Route::resource('/admin-users',AdminUserController::class);
    Route::patch('/admin-users/{id}/password',[AdminUserController::class, 'updatePassword']);
});