<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductTypeController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'authenticate']);

Route::middleware(['auth:web'])->group(function () {
    Route::get('/auth', [AuthController::class, 'fetchAuth']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::resource('/admin-users', AdminUserController::class);
    Route::patch('/admin-users/{admin_user}/password', [AdminUserController::class, 'updatePassword']);

    Route::resource('/product-types', ProductTypeController::class);
    Route::resource('/brands', BrandController::class);
});
