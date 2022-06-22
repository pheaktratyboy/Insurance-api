<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyUserController;
use Illuminate\Support\Facades\Route;

/** Company **/
Route::get('companies', [CompanyController::class,'index'])->name('companies.index');

Route::get('companies/{company}', [CompanyController::class,'show'])->name('companies.show');


Route::post('companies', [CompanyController::class,'store'])->name('companies.store');
Route::put('companies/{company}', [CompanyController::class,'update'])->name('companies.update');
Route::delete('companies/{company}', [CompanyController::class,'destroy'])->name('companies.destroy');

Route::post('companies/assignSubscriberToCompany', [CompanyController::class,'assignSubscriberToCompany'])->name('companies.assign_subscriber_to_company');


/** Company Users **/
Route::get('company-users/{company}', [CompanyUserController::class,'indexUsers'])->name('company_users.index_user');
Route::get('company-subscriber/{company}', [CompanyUserController::class,'indexSubscriber'])->name('company_users.index_subscriber');
Route::get('company-users/{company_users}', [CompanyUserController::class,'show'])->name('company_users.show');


Route::post('company-users/{company}', [CompanyUserController::class,'store'])->name('company_users.store');
Route::put('company-users/{company}', [CompanyUserController::class,'update'])->name('company_users.update');
Route::delete('company-users/{company_user}', [CompanyUserController::class,'destroy'])->name('company_users.destroy');
