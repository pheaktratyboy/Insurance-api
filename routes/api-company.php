<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::get('companies', [CompanyController::class,'index'])->name('companies.index');
Route::get('companies/{company}', [CompanyController::class,'show'])->name('companies.show');

Route::post('companies', [CompanyController::class,'store'])->name('companies.store');
Route::put('companies/{company}', [CompanyController::class,'update'])->name('companies.update');