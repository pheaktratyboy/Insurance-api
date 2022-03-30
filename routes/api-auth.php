<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/** login process */
Route::post('/auth/login', [AuthenticationController::class,'employeeLogin'])->name('auth.login');

/** change password process */
Route::post('auth/change-password', [AuthenticationController::class,'changePassword'])->name('auth.change_password');
