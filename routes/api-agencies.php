<?php

use App\Http\Controllers\AgencyController;
use Illuminate\Support\Facades\Route;

Route::get('agencies', [AgencyController::class,'index'])->name('agencies.index');
Route::get('agencies/{user}', [AgencyController::class,'show'])->name('agencies.show');

Route::post('agencies', [AgencyController::class,'store'])->name('agencies.store');
Route::put('agencies/{user}', [AgencyController::class,'update'])->name('agencies.update');
