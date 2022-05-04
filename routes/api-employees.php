<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('employees', [EmployeeController::class,'index'])->name('employees.index');
Route::get('employees/getAllStaff', [EmployeeController::class,'getAllStaff'])->name('employees.getAllStaff');
Route::get('employees/{user}', [EmployeeController::class,'show'])->name('employees.show');

Route::post('employees/store-by-user', [EmployeeController::class,'store'])->name('employees.store_by_user');
Route::put('employees/{employee}', [EmployeeController::class,'update'])->name('employees.update');
