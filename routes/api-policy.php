<?php

use App\Http\Controllers\PolicyController;
use Illuminate\Support\Facades\Route;

Route::post('policies', [PolicyController::class,'store'])->name('policies.store');
Route::put('policies/{policy}', [PolicyController::class,'update'])->name('policies.update');
Route::get('policies', [PolicyController::class,'index'])->name('policies.index');
Route::get('policies/{policy}', [PolicyController::class,'show'])->name('policies.show');
