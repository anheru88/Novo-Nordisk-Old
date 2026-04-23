<?php

namespace App\Imports;

use App\CreditNotesClients;
use App\CreditNotesClientsBills;
use App\CreditNotesDetails;
use App\CreditNotesDetailsBills;
use App\Traits\GenericTrait;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GenSheetImport implements ToModel,WithHeadingRow,WithChunkReading
{

    use Importable, GenericTrait;


    public function __construct($id , $tabName)
    {
        $this->id_note  = $id;
        $this->tabName  = $tabName;
    }

    public function model(array $row)
    {

        $clients = CreditNotesClients::where('client_sap_code',$row['codigo_cliente'])
        ->where('id_credit_notes',$this->id_note)
        ->where('concept',$row['concepto'])
        //->where('bill',$row['factura'])
        ->pluck('id_credit_notes_clients');


        $clientsBill = CreditNotesClientsBills::where('client_sap_code',$row['codigo_cliente'])
        ->where('id_credit_notes',$this->id_note)
        ->where('concept',$row['concepto'])
        ->where('bill',$row['factura'])
        ->pluck('id_credit_notes_clients_b');

        // Creacion de datos para generar el excel
        if(sizeof($clientsBill) > 0){
            $id_clientB = $clientsBill[0];
        }else{
            $clientBill = $this->createNoteClientBill($row, $this->id_note);
            $id_clientB = $clientBill->id_credit_notes_clients_b;
        }
        $creditNoteDetailsBill = $this->createNotesDetailsBill($row, $id_clientB, $this->tabName);


        // Creación de datos para generar el SAP Archivo plano
        if(sizeof($clients) > 0){
            $id_client = $clients[0];
        }else{
            $client = $this->createNoteClient($row, $this->id_note);
            $id_client = $client->id_credit_notes_clients;
        }

        $creditNoteDetails = $this->createNotesDetails($row,$id_client, $this->tabName);

        return $creditNoteDetails;

    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
