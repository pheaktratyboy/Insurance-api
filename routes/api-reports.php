<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('reports/audience', [ReportController::class,'reportAudience'])->name('reports.audience');
Route::get('reports/yearly', [ReportController::class,'reportYearly'])->name('reports.yearly');
Route::get('reports/dashboard', [ReportController::class,'reportDashboard'])->name('reports.dashboard');
Route::get('reports/subscriber', [ReportController::class,'reportSubscriber'])->name('reports.subscriber');
Route::get('reports/topAgency', [ReportController::class,'reportTopAgency'])->name('reports.top_agency');
Route::get('reports/monthlyTransaction', [ReportController::class,'reportMonthlyTransaction'])->name('reports.monthly_transaction');
