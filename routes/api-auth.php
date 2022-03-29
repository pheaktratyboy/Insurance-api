<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;


/** change password process */
Route::post('auth/change-password', [AuthenticationController::class,'changePassword'])->name('auth.change_password');
