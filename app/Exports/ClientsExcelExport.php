<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class ClientsExcelExport implements FromQuery, WithHeadings, ShouldQueue, WithMapping
{
    use Exportable;
    
    public function __construct($oldReq)
    {
        $this->oldReq = $oldReq;
    }

    public function headings(): array
    {
        return [
            '# cliente',
            'Nombre cliente',
            'Client NIT',
            'Canal',
            'Tipo de cliente',
            'Departamento',
            'Ciudad',
            'Código SAP',
            'Cupo',
            'Forma de pago',
            'Estado del cliente',
            // contacto
            'Nombre de contacto',
            'Cargo de contacto',
            'Dirección',
            'Teléfono',
            'Email de contacto principal',
            'Otros email de contacto',
            'CAM'
        ];
    }

    public function query()
    {
        $result = DB::table('nvn_clients')
        ->select(
            'nvn_clients.id_client',
            'nvn_clients.client_name',
            'nvn_clients.client_nit',
            'nvn_dist_channels.channel_name',
            'nvn_client_types.type_name',
            'nvn_locations.loc_name',
            // 'nvn_locationscity.loc_name',
            'nvn_clients.client_sap_code',
            'nvn_clients.client_credit',
            'nvn_payment_terms.payterm_name',
            'nvn_clients.active',
            'nvn_clients.client_contact',
            'nvn_clients.client_position',
            'nvn_clients.client_address',
            'nvn_clients.client_phone',
            'nvn_clients.client_email',
            'nvn_clients.client_email_secondary',
            'c.nickname AS cam',
            )
        ->join('nvn_dist_channels','nvn_dist_channels.id_channel', '=','nvn_clients.id_client_channel')
        ->join('nvn_client_types','nvn_client_types.id_type',      '=','nvn_clients.id_client_type')
        ->join('nvn_locations','nvn_locations.id_locations',       '=','nvn_clients.id_department')
        // ->join('nvn_locationscity','nvn_locations.id_locations',   '=','nvn_clients.id_city')
        ->join('users as c','c.id',                                '=','nvn_clients.id_diab_contact')
        ->join('nvn_payment_terms','nvn_payment_terms.id_payterms','=','nvn_clients.id_payterm')
        ->orderBy('nvn_clients.id_client', 'ASC');
        // ->where('nvn_quotations_details.is_valid', 1)

        // dd($this->oldReq);

        if ($this->oldReq['user'] != "") {
            $result = $result->where('id_diab_contact', $this->oldReq['user']);
        }
        if ($this->oldReq['payterm'] != "") {
            $result = $result->where('id_payterm', $this->oldReq['payterm']);
        }
        if ($this->oldReq['channel'] != "") {
            $result = $result->where('id_client_channel', $this->oldReq['channel']);
        }
        if ($this->oldReq['typeclient'] != "") {
            $result = $result->where('id_client_type', $this->oldReq['typeclient']);
        }
        if ($this->oldReq['active'] != "") {
            $result = $result->where('active', $this->oldReq['active']);
        }
        if ($this->oldReq['id_department'] != "") {
            $result = $result->where('id_department', $this->oldReq['id_department']);
        }
        if ($this->oldReq['id_city'] != "") {
            $result = $result->where('id_city', $this->oldReq['id_city']);
        }

        return $result;
    }

    public function map($result): array
    {
        return [
            $result->id_client,
            $result->client_name,
            $result->client_nit,
            $result->channel_name,
            $result->type_name,
            $result->loc_name,
            $result->loc_name,
            $result->client_sap_code,
            $result->client_credit,
            $result->payterm_name,
            statusExcelCli($result->active),
            // contacto
            $result->client_contact,
            $result->client_position,
            $result->client_address,
            $result->client_phone,
            $result->client_email,
            $result->client_email_secondary,
            $result->cam
        ];
    }
}
