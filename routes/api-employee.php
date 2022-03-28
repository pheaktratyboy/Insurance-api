<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::post('employees', [EmployeeController::class,'store'])->name('employees.store');
Route::put('employees/{employees}', [EmployeeController::class,'update'])->name('employees.update');
Route::get('employees/{employeesId}', [EmployeeController::class,'show'])->name('employees.show');
