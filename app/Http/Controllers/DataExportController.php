<?php

namespace App\Http\Controllers;

use App\Exports\ReportSubscriberDetailExport;
use App\Exports\ReportSubscriberExport;

class DataExportController extends Controller
{
    public function exportReportSubscriber()
    {
        return (new ReportSubscriberExport)->download('report_subscriber.xlsx');
    }

    public function exportReportSubscriberDetail()
    {
        return (new ReportSubscriberDetailExport)->download('report_subscriber_detail.xlsx');
    }
}
