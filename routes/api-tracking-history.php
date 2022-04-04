<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingHistoryController;

Route::get('tracking-histories', [TrackingHistoryController::class,'index'])->name('tracking_histories.index');
Route::get('tracking-histories/{tracking_history}', [TrackingHistoryController::class,'show'])->name('tracking_histories.show');
