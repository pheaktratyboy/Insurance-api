<?php

use App\Http\Controllers\AgencyController;
use Illuminate\Support\Facades\Route;

Route::get('agencies', [AgencyController::class,'index'])->name('agencies.index');
Route::get('agencies/all', [AgencyController::class,'getAllAgency'])->name('agencies.getAllAgency');
Route::get('agencies/{agency}', [AgencyController::class,'show'])->name('agencies.show');

Route::post('agencies', [AgencyController::class,'store'])->name('agencies.store');
Route::put('agencies/{agency}', [AgencyController::class,'update'])->name('agencies.update');
