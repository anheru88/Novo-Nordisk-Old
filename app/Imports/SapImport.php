<?php

namespace App\Imports;

use App\NegotiationConcepts;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Illuminate\Support\Facades\View;


class SapImport implements WithMultipleSheets, SkipsUnknownSheets
{
    use WithConditionalSheets;
   protected $id_credit_notes;
    public function __construct($id)
    {
        $this->id_credit_notes = $id;
    }


// refactorizar codigo 
private function initializeSheets(): array
{
    $concepts = NegotiationConcepts::where('sap_concept', '!=', '')->pluck('sap_concept');
    $sheets = [];

    $scan = [
        'NC ESCALAS', 'NC INFORMACIÓN', 'NC PRESENTACIÓN', 'NC PRESENTACIÓN PACK X 3',
        'NC COMERCIAL', 'NC CONVENIO', 'NC CODIFICACIÓN', 'NC PAGO', 'NC PROVISIÓN',
        'NC CORTA EXPIRA', 'NC NEGOCIACIÓN ESPECIAL', 'NC ADHERENCIA', 'NC LOGISTICA',
        'NC LANZAMIENTO', 'NC CAPITA'
    ];

    foreach ($scan as $key => $concept) {
        $sheets[$concept] = new GenSheetImport($this->id_credit_notes, $concept);
    }

    foreach ($concepts as $key => $concept) {
        $sheets[$concept] = new GenSheetImport($this->id_credit_notes, $concept);
    }

    return $sheets;
}

public function conditionalSheets(): array
{
    return $this->initializeSheets();
}

public function sheets(): array
{
    return $this->initializeSheets();
}



    public function onUnknownSheet($sheetName)
    {
        $concepts = NegotiationConcepts::where('sap_concept', '!=', '')->pluck('sap_concept');
        $temp = [];
        $sheets = [];
       
        foreach ($concepts as $key => $concept) {
            $temp[$key] = [$concept => new GenSheetImport($this->id_credit_notes, $concept)];
            $sheets[$concept] = new GenSheetImport($this->id_credit_notes, $concept);
        }

        $res = array_merge($temp, $sheets);

        return $sheetName;
    }


    public function UnknownSheets($request, $sheetName)
    {
        $concepts = NegotiationConcepts::where('sap_concept', '!=', '')->pluck('sap_concept');
        $temp = [];
        $sheets = [];
        $hoja_de_credito = $request->input('nombre_nota_credito');
        $sheetName = $hoja_de_credito;
        $this->hojaDeCredito = $sheetName;
        foreach ($concepts as $key => $concept) {
            $temp[$key] = [$concept => new GenSheetImport($this->id_credit_notes, $concept)];
            $sheets[$concept] = new GenSheetImport($this->id_credit_notes, $concept);
        }

        $res = array_merge($temp, $sheets);
        $res[$this->hojaDeCredito] = new GenSheetImport($this->id_credit_notes, $this->hojaDeCredito);

        return $sheetName;
    }

}

