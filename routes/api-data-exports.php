<?php

use App\Http\Controllers\DataExportController;
use Illuminate\Support\Facades\Route;

Route::get('exports/report-subscriber', [DataExportController::class, 'exportReportSubscriber']);
Route::get('exports/report-subscriber-detail', [DataExportController::class, 'exportReportSubscriberDetail']);
