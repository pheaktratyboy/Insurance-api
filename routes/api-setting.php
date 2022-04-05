<?php

use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('settings', [SettingController::class,'index'])->name('settings.index');
Route::get('settings/reminder', [SettingController::class,'getReminder'])->name('settings.get_reminder');
Route::get('settings/company', [SettingController::class,'getCompany'])->name('settings.get_company');

Route::put('settings/reminder', [SettingController::class,'updateReminder'])->name('settings.reminder');
