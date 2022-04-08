<?php

namespace App\Exports;

use App\Http\Resources\ExportSubscriberReportResource;
use App\Service\ReportService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportSubscriberExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return [
            'Name Khmer',
            'Name English',
            'Identity Number',
            'Date Of Birth',
            'Phone Number',
            'Address',
            'Place Of Birth',
            'Gender',
            'Religion',
            'Company Name',
            'Policy Name',
            'Payment Method',
            'Expired At',
            'Status',
        ];
    }

    public function collection()
    {
        $report = new ReportService();
        return ExportSubscriberReportResource::collection($report->exportSubscribers());
    }
}
