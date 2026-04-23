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

class ArpSimulationsSheets implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting
{
    private $nameProduct;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($nameProduct, $sheetColor, $products, $arp, $services, $pbc, $priceListProducts,$escalas, $info, $financiero)
    {
        $this->nameProduct          = $nameProduct;
        $this->sheetColor           = $sheetColor;
        $this->products             = $products;
        $this->arp                  = $arp;
        $this->services             = $services;
        $this->escalas              = $escalas;
        $this->info                 = $info;
        $this->financiero           = $financiero;
        $this->pbc                  = $pbc;
        $this->servicesSize         = sizeof($services);
        $this->priceListProducts    = $priceListProducts;
        $this->cols                 = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
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
        $colStartT  = $this->servicesSize + 14;
        $cellRows   = $this->servicesSize;
        return [
            AfterSheet::class    => function (AfterSheet $event) use($colStartT, $cellRows) {
                $cellRange = "B$colStartT:I$colStartT"; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('000000');
                $event->sheet->getDelegate()->getStyle($cellRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('acc1ff');
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal('left');

                $borderCell =  $colStartT - 4;
                $borderCell2 =  $colStartT - 4 + $cellRows;

                $cellRange2 = "D$borderCell:F$borderCell2";
                $event->sheet->getDelegate()->getStyle($cellRange2)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000'],
                        ],
                    ]
                ])->getFont()->setSize(12)->setBold(true)->getColor();
                $event->sheet->getDelegate()->getStyle("D$borderCell:D$borderCell2")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('c6efce');

                $event->sheet->getStyle("B$borderCell:C$borderCell")->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000'],
                        ],
                    ]
                ])->getFont()->setSize(12)->setBold(true)->getColor();

                $color2 = $colStartT-1;
                $cellRange3 = "M$color2";
                $event->sheet->getDelegate()->getStyle($cellRange3)->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('ffffff');
                $event->sheet->getDelegate()->getStyle($cellRange3)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('002060');
                $event->sheet->getDelegate()->getTabColor()->setARGB($this->sheetColor);

                $cellRange4 = "J$colStartT:AF$colStartT"; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange4)->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('ffffff');
                $event->sheet->getDelegate()->getStyle($cellRange4)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($this->sheetColor);
                $event->sheet->getDelegate()->getStyle($cellRange4)->getAlignment()->setHorizontal('left');

                $cellRange5 = 'B1:AF14'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange5)->getFont()->setSize(12)->getColor()->setRGB('000000');
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
        $nameC              = $this->nameProduct;
        $color              = $this->sheetColor;
        $products           = $this->products;
        $arp                = $this->arp;
        $services           = $this->services;
        $escalas            = $this->escalas;
        $info               = $this->info;
        $financiero         = $this->financiero;
        $pbc                = $this->pbc;
        $startProd          = $this->servicesSize + 15;
        $priceListProducts  = $this->priceListProducts;
        $rowARP             = $startProd - 5;
        $endProd            = $startProd + sizeof($products) - 1;
        $cols               = $this->cols;
        $servicesSize       = $this->servicesSize;

        $ncEscalas = [];
        return view('admin.arp.xls.tablearp', compact('ncEscalas','nameC','color', 'products','arp','services','pbc','startProd','endProd','cols','priceListProducts','escalas','info','financiero','servicesSize'));
    }


}
