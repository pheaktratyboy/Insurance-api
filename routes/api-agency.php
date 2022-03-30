<?php

use App\Http\Controllers\AgencyController;
use Illuminate\Support\Facades\Route;

Route::post('agencies', [AgencyController::class,'store'])->name('agencies.store');
Route::put('agencies/{agency}', [AgencyController::class,'update'])->name('agencies.update');
