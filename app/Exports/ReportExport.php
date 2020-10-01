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


class ReportExport implements FromView, WithDrawings, WithEvents, ShouldAutoSize
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

                $cellRange = 'A1:A'. $highestRow;
              
                $event->sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
                $event->sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(20);
                $event->sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(30);
                $event->sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(50);


                    $event->sheet->styleCells(
                        'A' . $event->sheet->getHighestRow(),
                        [
                            'font'  => [
                                'bold'  => false,
                                'color' => array('rgb' => '000000'),
                                'size'  => 22,
                                'name'  => 'DINCond-Regular',
                                'italic'
                            ],
                            'alignment' => [
                                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                        ],
                           
                            
                        ]
                    );
                $event->sheet->styleCells(
                    'F5:F7',
                    [
                        'font'  => [
                            //'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 11,
                            'name'  => 'Helvetica Neue'
                            ]
                        
                    ]
                );
                
            },
        ];
    }
    public function view(): View
    {
        
        return view('exports.report', [
            'data' => $this->data,
        ]);
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/img/htf/logo-export.png'));
        $drawing->setWidth(70);
        $drawing->setCoordinates('A1');

       

        return [$drawing];
    }
    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }
}
