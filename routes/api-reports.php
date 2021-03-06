<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('reports/reportDashboard', [ReportController::class,'reportDashboard'])->name('reports.report_dashboard');
Route::get('reports/reportSubscriber', [ReportController::class,'reportSubscriber'])->name('reports.report_subscriber');
