<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('roles/list-all', [RoleController::class,'listAll'])->name('roles.list_all');
Route::get('roles', [RoleController::class,'index'])->name('roles.index');

