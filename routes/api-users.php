<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('users/all', [UserController::class,'getAllUser'])->name('users.get_all_user');
Route::get('users', [UserController::class,'index'])->name('users.index');
Route::get('users/{user}', [UserController::class,'show'])->name('users.show');
Route::get('my-profile', [UserController::class,'showMyProfile'])->name('users.show_my_profile');

Route::put('users/{user}', [UserController::class,'update'])->name('users.update');
Route::put('users/force-change-password/{user}', [UserController::class,'forceChangePassword'])->name('users.force_change_password');
