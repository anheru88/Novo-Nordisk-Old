<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InvoicesExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct($concepts, $nameConcepts, $idSale)
    {
        $this->concepts = $concepts;
        $this->name_concepts = $nameConcepts;
        $this->idSale = $idSale;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->concepts as $key => $concept) {
            $sheets[] = new NotesExport($concept, $this->name_concepts[$key], $this->idSale);
        }
        //$sheets[] = new NotesExport($this->concepts[13], $this->name_concepts[13], $this->idSale);
        return $sheets;
    }
}
