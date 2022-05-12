<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('reports/yearly', [ReportController::class,'reportYearly'])->name('reports.report_yearly');
Route::get('reports/dashboard', [ReportController::class,'reportDashboard'])->name('reports.report_dashboard');
Route::get('reports/subscriber', [ReportController::class,'reportSubscriber'])->name('reports.report_subscriber');
