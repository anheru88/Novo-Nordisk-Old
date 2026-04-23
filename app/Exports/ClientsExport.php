<?php

namespace App\Exports;

use App\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClientsExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'Cliente',
            'Nit',
            'Correo',
            'Dirección',
            'Cargo',
            'Teléfono',
            'Canal',
            'Codigo SAP',
            'Tipo de cliente',
            'Forma de pago',
            'Cupo',
            'Estado',
        ];
    }

    public function collection()
    {
        $roles = auth()->user()->roles;
        if ($roles[0]->slug == "cam") {
            $clientes = Client::where('id_diab_contact',auth()->user()->id)->orderBy('id_client','DESC')->with('city','channel','payterm','type')->get();
        }else{
            $clientes = Client::orderBy('id_client','DESC')->with('city','channel','payterm','type')->get();
        }
        $finded = [];
        foreach ($clientes as $key => $cliente) {
            $finded[$key]['client_name'] = $cliente->client_name;
            $finded[$key]['client_nit'] = $cliente->client_nit;
            $finded[$key]['client_email'] = $cliente->client_email;
            $finded[$key]['client_address'] = $cliente->client_address;
            $finded[$key]['client_position'] = $cliente->client_position;
            $finded[$key]['client_phone'] = $cliente->client_phone;
            $finded[$key]['channel_name'] = $cliente->channel->channel_name;
            $finded[$key]['client_sap_code'] = $cliente->client_sap_code;
            $finded[$key]['type_name'] = $cliente->type->type_name;
            if (!empty($cliente->payterm)) {
                $finded[$key]['payterm_name'] = paytermName($cliente->payterm, $cliente->payterm->payterm_name);
            } else {
                $finded[$key]['payterm_name'] = '-.-';
            }            
            $finded[$key]['client_credit'] = number_format($cliente->client_credit,0, ",", ".");
            $finded[$key]['active'] = clientActive($cliente->active);
        }
        return new Collection([$finded]);
    }
}