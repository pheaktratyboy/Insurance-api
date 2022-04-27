<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('users/all', [UserController::class,'getAllUser'])->name('users.get_all_user');
Route::get('users', [UserController::class,'index'])->name('users.index');
Route::get('users/{user}', [UserController::class,'show'])->name('users.show');
Route::put('users/{user}', [UserController::class,'update'])->name('users.update');
