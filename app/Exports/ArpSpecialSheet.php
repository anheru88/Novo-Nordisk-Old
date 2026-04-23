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

class ArpSpecialSheet implements FromView, WithTitle
{
    private $nameProduct;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($nameProduct)
    {
        $this->nameProduct          = $nameProduct;
    }

    public function title(): string
    {
        return  $this->nameProduct;
    }


    public function view(): View
    {

        return view('admin.arp.xls.versionarp');
    }


}
