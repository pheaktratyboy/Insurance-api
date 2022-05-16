<?php

use App\Http\Controllers\ClaimController;
use Illuminate\Support\Facades\Route;

Route::get('claims', [ClaimController::class,'index'])->name('claim.index');
Route::get('claims/{claim}', [ClaimController::class,'show'])->name('claim.show');

Route::post('claims', [ClaimController::class,'store'])->name('claim.store');
Route::post('claims/approved/{claim}', [ClaimController::class,'approvedClaim'])->name('claim.approved');
Route::post('claims/cancel/{claim}', [ClaimController::class,'cancelClaim'])->name('claim.cancel');
Route::post('claims/rejected/{claim}', [ClaimController::class,'rejectedClaim'])->name('claim.rejected');

Route::put('claims/{claim}', [ClaimController::class,'update'])->name('claim.update');
Route::delete('claims/{claim}', [ClaimController::class,'destroy'])->name('claim.destroy');
