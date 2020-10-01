<?php

namespace App\Exports;

use App\User;
use App\Organization;
use App\Category;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SubscriptionDancerExport implements FromView, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    private $data;

    public function __construct($data)
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });
  

        $this->data = $data;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                
                $sheet = $event->sheet;
                $highestRow = $event->sheet->getHighestRow()+1;
            },
        ];
    }
    public function view(): View
    {
        
        return view('exports.subscriptionDancer', [
            'data' => $this->data,
        ]);
    }
    // public function drawings()
    // {
    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('Hit the floor');
    //     $drawing->setPath(public_path('/img/export-excel-header.jpg'));
    //    $drawing->setWidth(984);
    //     $drawing->setCoordinates('A1');

       

    //     return [$drawing];
    // }
    public function columnFormats(): array
    {
        // return [
        //     'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        //     'C' => NumberFormat::FORMAT_DATE_TIME3,
        // ];
        return [];
    }
}
