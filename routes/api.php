<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;


Route::post('/login',[AuthController::class, 'authenticate']);

Route::resource('/admin_users',AdminUserController::class);
Route::patch('/admin_users/{id}/password',[AdminUserController::class, 'updatePassword']);
