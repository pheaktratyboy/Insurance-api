<?php

use App\Http\Controllers\MunicipalityController;
use Illuminate\Support\Facades\Route;

Route::post('municipalities', [MunicipalityController::class,'store'])->name('municipalities.store');
Route::put('municipalities/{municipality}', [MunicipalityController::class,'update'])->name('municipalities.update');
Route::get('municipalities', [MunicipalityController::class,'index'])->name('municipalities.index');
Route::get('municipalities/{municipality}', [MunicipalityController::class,'show'])->name('municipalities.show');
