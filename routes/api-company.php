<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyUserController;
use Illuminate\Support\Facades\Route;

/** Company **/
Route::get('companies', [CompanyController::class,'index'])->name('companies.index');
Route::get('companies/{company}', [CompanyController::class,'show'])->name('companies.show');
Route::get('companies/listSubscriber', [CompanyController::class,'listAllSubscriber'])->name('companies.list_all_subscriber');


Route::post('companies', [CompanyController::class,'store'])->name('companies.store');
Route::put('companies/{company}', [CompanyController::class,'update'])->name('companies.update');
Route::delete('companies/{company}', [CompanyController::class,'destroy'])->name('companies.destroy');


/** Company Users **/
Route::get('company-users', [CompanyUserController::class,'index'])->name('company_users.index');
Route::get('company-users/{company_users}', [CompanyUserController::class,'show'])->name('company_users.show');

Route::post('company-users/{company}', [CompanyUserController::class,'store'])->name('company_users.store');
Route::put('company-users/{company}', [CompanyUserController::class,'update'])->name('company_users.update');
Route::delete('company-users/{company_user}', [CompanyUserController::class,'destroy'])->name('company_users.destroy');
