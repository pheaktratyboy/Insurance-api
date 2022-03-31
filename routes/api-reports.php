<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('reports/countExpired', [ReportController::class,'countExpired'])->name('reports.count_expired');
Route::get('reports/countSubscriber', [ReportController::class,'countSubscriber'])->name('reports.count_subscriber');
