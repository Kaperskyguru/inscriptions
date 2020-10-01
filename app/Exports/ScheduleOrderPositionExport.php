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

class ScheduleOrderPositionExport implements FromView, WithEvents, ShouldAutoSize, WithColumnFormatting
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

                //$cellRange = 'A1:A'. $highestRow;
              
                //$event->sheet->getRowDimension('A')->setRowHeight(327);;
                //$event->sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(984);
                // $event->sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(50);
                // $sheet->setColumnFormat([
                //     'C' => "HH:mm:ss",
                // ]);

                    // $event->sheet->styleCells(
                    //     'C' . $event->sheet->getHighestRow(),
                    //     [
                    //         'font'  => [
                    //             'bold'  => false,
                    //             'color' => array('rgb' => '000000'),
                    //             'size'  => 22,
                    //             'name'  => 'DINCond-Regular',
                    //             'italic'
                    //         ],
                    //         'alignment' => [
                    //                         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    //                     ],
                           
                            
                //         ]
                //     );
                // $event->sheet->styleCells(
                //     'F5:F7',
                //     [
                //         'font'  => [
                //             //'bold'  => true,
                //             'color' => array('rgb' => '000000'),
                //             'size'  => 11,
                //             'name'  => 'Helvetica Neue'
                //             ]
                        
                //     ]
                // );
                
            },
        ];
    }
    public function view(): View
    {
        
        return view('exports.scheduleOrderPosition', [
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
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_DATE_TIME3,
        ];
    }
}