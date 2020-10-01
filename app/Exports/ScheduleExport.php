<?php

namespace App\Exports;

use App\Schedule;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ScheduleExport implements FromView, ShouldAutoSize
{
    private $data;

    public function __construct($data)
    {
        // Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
        //     $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        // });
            

        $this->data = $data;
    }
    public function view(): View
    {
        
        return view('exports.scheduleItem', [
            'data' => $this->data,
        ]);
    }
}
