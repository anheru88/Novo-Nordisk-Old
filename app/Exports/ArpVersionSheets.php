<?php

namespace App\Exports;

use Dompdf\Css\Stylesheet;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Formula;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ArpVersionSheets implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting
{
    private $nameProduct;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($nameProduct)
    {
        $this->nameProduct          = $nameProduct;
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->user->name,
            Date::dateTimeToExcel($invoice->created_at),
        ];
    }

    public function title(): string
    {
        return  $this->nameProduct;
    }

    public function registerEvents(): array
    {
        $colStartT  = 10;
        $cellRows   = 15;
        return [
            AfterSheet::class    => function (AfterSheet $event) use($colStartT, $cellRows) {
                $cellRange = "B$colStartT:I$colStartT"; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('000000');
                $event->sheet->getDelegate()->getStyle($cellRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('acc1ff');
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal('left');

                $borderCell =  $colStartT - 4;
                $borderCell2 =  $colStartT - 4 + $cellRows;

                $event->sheet->getStyle("B$borderCell:C$borderCell")->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000'],
                        ],
                    ]
                ])->getFont()->setSize(12)->setBold(true)->getColor();


            }
        ];
    }

    public function columnFormats(): array
    {
        return [

        ];
    }

    public function view(): View
    {

        return view('admin.arp.xls.versionarp');
    }


}
