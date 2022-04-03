<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('notifications', [NotificationController::class,'index'])->name('notifications.index');
Route::get('notifications/{notification}', [NotificationController::class,'show'])->name('notifications.show');

Route::post('notifications', [NotificationController::class,'store'])->name('notifications.store');
Route::put('notifications/{notification}', [NotificationController::class,'update'])->name('notifications.update');
