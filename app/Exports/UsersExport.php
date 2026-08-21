<?php

namespace App\Exports;

use App\Models\AttendanceDetail;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UsersExport implements WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;
    public const FILE_NAME='user.xlsx';
    public function __construct($data){
        $this->data=$data;
    }
    public function registerEvents():array
    {
        return[
            AfterSheet::class=>function(AfterSheet $event){
                $sheet=$event->sheet->getDelegate();
                $sheet->getStyle('B7:K7')->applyFromArray([
                    'fill'=>[
                        'fillType'=>'solid',
                        'color'=>['rgb'=>'CCCCFF']
                    ],
                    'alignment'=>[
                        'horizontal'=>'center',
                        'vertical'=>'center'
                    ]
                ]);
                $sheet->getStyle('H2:I3')->applyFromArray([
                    'fill'=>[
                        'fillType'=>'solid',
                        'color'=>['rgb'=>'CCFFCC']
                    ],
                    'alignment'=>[
                        'horizontal'=>'center',
                        'vertical'=>'center'
                    ]
                ]);
                $sheet->getStyle('B2:B4')->applyFromArray([
                    'fill'=>[
                        'fillType'=>'solid',
                        'color'=>['rgb'=>999999]
                    ]
                ]);
                $sheet->getStyle("B2:D4")->applyFromArray([
                    'borders'=>[
                        'allBorders'=>[
                            'borderStyle'=>'thin',
                            'color'=>['rgb'=>'000000']
                        ]
                    ]
                ]);
                $sheet->getStyle("H2:J3")->applyFromArray([
                    'borders'=>[
                        'allBorders'=>[
                            'borderStyle'=>'thin',
                            'color'=>['rgb'=>'000000']
                        ]
                    ]
                ]);
                $sheet->getStyle('A1:Z100')->applyFromArray([
                    'font'=>[
                        'size'=>4
                    ]
                ]);
                for($i=1;$i<=100;$i++){
                    $sheet->getRowDimension($i)->setRowHeight(10);
                }
                $sheet->getRowDimension(7)->setRowHeight(20);
                $event->sheet->setCellValue('B2','会社名');
                $event->sheet->setCellValue('C2','株式会社〇〇システムズ');
                $event->sheet->setCellValue('B3','社員番号');
                $event->sheet->setCellValue('C3',$this->data['attendance_heads'][0]->user->user_no);
                $event->sheet->setCellValue('B4','氏名');
                $event->sheet->setCellValue('C4',$this->data['attendance_heads'][0]->user->name);
                $event->sheet->setCellValue('B6',"{$this->data['year']}年{$this->data['month']}月");
                $event->sheet->setCellValue('H2','勤務時間合計');
                $event->sheet->setCellValue('H3','残業時間合計');
                $event->sheet->setCellValue('B7','日');
                $event->sheet->setCellValue('C7','曜日');
                $event->sheet->setCellValue('D7','区分');
                $event->sheet->setCellValue('E7','開始時刻');
                $event->sheet->setCellValue('F7','終了時刻');
                $event->sheet->setCellValue('G7','昼休憩時間(h)');
                $event->sheet->setCellValue('H7','夜休憩時間(h)');
                $event->sheet->setCellValue('I7','勤務時間');
                $event->sheet->setCellValue('J7','残業時間');
                $event->sheet->setCellValue('K7','備考');
                $sheet->mergeCells('C2:D2');
                $sheet->mergeCells('C3:D3');
                $sheet->mergeCells('C4:D4');
                $sheet->mergeCells('H2:I2');
                $sheet->mergeCells('H3:I3');
                foreach(range('B','E') as $col){
                    $sheet->getColumnDimension($col)->setWidth(5);
                }
                $sheet->getColumnDimension('A')->setWidth(2);
                $sheet->getColumnDimension('K')->setWidth(20);
            }
        ];
    }
}
