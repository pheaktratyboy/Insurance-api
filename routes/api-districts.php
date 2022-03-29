<?php

use App\Http\Controllers\DistrictController;
use Illuminate\Support\Facades\Route;

Route::get('districts', [DistrictController::class,'index'])->name('districts.index');
Route::get('districts/{district}', [DistrictController::class,'show'])->name('districts.show');
Route::post('districts', [DistrictController::class,'store'])->name('districts.store');
Route::put('districts/{district}', [DistrictController::class,'update'])->name('districts.update');
