<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('users/{user}', [UserController::class,'show'])->name('users.show');
Route::put('users/{user}', [UserController::class,'update'])->name('users.update');
