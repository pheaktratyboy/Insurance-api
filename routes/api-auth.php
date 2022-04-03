<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('auth/profile', [UserController::class,'showProfile'])->name('auth.show_profile');
Route::post('auth/logout', [AuthenticationController::class,'logout'])->name('auth.logout');
