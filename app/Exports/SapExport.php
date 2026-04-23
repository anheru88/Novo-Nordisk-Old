<?php

namespace App\Exports;

use App\CreditNotes;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SapExport implements FromView,WithColumnFormatting
{


    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'E' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    public function __construct($id)
    {
        $this->id_crenote = $id;
    }
    public function view(): View
    {
        $notes = CreditNotes::where('id_credit_notes',$this->id_crenote)->with('productsBills')->get();
        //dd($notes[0]);
        return view('admin.notas.tablesapexcel', ['notes' => $notes]);
    }
}


