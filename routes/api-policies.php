<?php

use App\Http\Controllers\PolicyController;
use Illuminate\Support\Facades\Route;

Route::get('policies', [PolicyController::class,'index'])->name('policies.index');
Route::get('policies/{policy}', [PolicyController::class,'show'])->name('policies.show');

Route::post('policies', [PolicyController::class,'store'])->name('policies.store');
Route::put('policies/{policy}', [PolicyController::class,'update'])->name('policies.update');
Route::delete('policies/{policy}', [PolicyController::class,'destroy'])->name('policies.destroy');
