<?php

namespace App\Exports;

//
// Use Block
//
use App\Models\AuditLog;

use Illuminate\Contracts\Support\Responsable;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Auto size the cell width
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
//

class AuditLogsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize, Responsable
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = AuditLog::with(['admin', 'target']);

        // Search
        if ($this->request->filled('search')) {
            $search = $this->request->search;

            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhereHas('admin', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('target', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Date from
        if ($this->request->filled('from')) {
            $query->whereDate('created_at', '>=', $this->request->from);
        }

        // Date to
        if ($this->request->filled('to')) {
            $query->whereDate('created_at', '<=', $this->request->to);
        }

        return $query->latest();
    }

    //
    // Set header for the data.
    //
    public function headings(): array
    {
        return [
            'Admin Name',
            'Action',
            'User Name',
            'User Email',
            'Date'
        ];
    }

    //
    // Map the data into the right position.
    //
    public function map($log): array
    {
        return [
            $log->admin->name,
            $log->action,
            $log->target->name,
            $log->target->email,
            $log->created_at->toDateTimeString()
        ];
    }

    //
    // Set style for the excel.
    //
    public function styles(Worksheet $sheet)
    {
        return [
            // Row 1 is the header row.
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00']
                ]
            ]
        ];
    }

    
    public function registerEvents(): array
    {
        //  Make the header stay on the top while scrolling down.
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function ($event) {
                $event->sheet->freezePane('A2');
            },
        ];
    }

    public function toResponse($request)
    {
        $now = now(); // Standalone variable to avoid time delaying.
        $dateFormat = $now->format('Y-m-d');
        $timeFormat = $now->format('H-i');
        $filename = "Audit-Logs_{$dateFormat}_at_{$timeFormat}.xlsx";

        return Excel::download($this, $filename, ExcelFormat::XLSX);
    }
}
