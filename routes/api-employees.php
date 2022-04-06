<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('employees', [EmployeeController::class,'index'])->name('employees.index');
Route::get('employees/getAllStaff', [EmployeeController::class,'getAllStaff'])->name('employees.getAllStaff');
Route::get('employees/{employee}', [EmployeeController::class,'show'])->name('employees.show');


Route::put('employees/{employee}', [EmployeeController::class,'update'])->name('employees.update');
