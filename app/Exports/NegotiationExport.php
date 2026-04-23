<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class NegotiationExport implements FromQuery, WithHeadings, ShouldQueue, WithMapping
{
    use Exportable;

    public function __construct($oldReq)
    {
        $this->oldReq = $oldReq;
    }

    public function headings(): array
    {
        return [
            '# Negociación',
            '# Cotización',
            'Escala de Cliente',
            'Concepto',
            'Aclaración',
            'Código SAP cliente',
            'Cliente',
            'Ciudad de Cliente',
            'Estado',
            'Canal',
            'CAM',
            'Código SAP producto',
            'Producto',
            'Marca',
            'Condición financiera',
            'Precio cotización',
            'Volumen',
            'Porcentaje de descuento',
            'Aprobada por',
            'Nivel de autorización',
            'Fecha de creación',
            'Vigente desde',
            'Vigente hasta',
        ];
    }

    public function query()
    {
        $results = DB::table('nvn_negotiations_details')
        ->select(
            'nvn_negotiations.negotiation_consecutive',
            'nvn_quotations.quota_consecutive',
            'nvn_negotiations_details.id_scale',
            'nvn_negotiations_details.id_concept',
            'nvn_negotiations_details.aclaracion',
            'nvn_clients.client_sap_code',
            'nvn_clients.client_name',
            'nvn_locations.loc_name',
            'nvn_negotiations.is_authorized',
            'nvn_negotiations.pre_approved',
            'nvn_negotiations.status_id',
            'nvn_dist_channels.channel_name',
            'c.nickname AS cam',
            'nvn_negotiations.id_authorizer_user',
            'nvn_products.id_product',
            'nvn_products.prod_sap_code',
            'nvn_products.prod_name',
            'nvn_brands.brand_name',
            'nvn_payment_terms.payterm_name',
            'nvn_quotations.quota_value',
            'nvn_negotiations_details.observations',
            'nvn_negotiations_details.discount',
            'nvn_negotiations.id_authorizer_user',
            'nvn_negotiations.id_auth_level',
            'nvn_negotiations.created_at',
            'nvn_negotiations.negotiation_date_ini',
            'nvn_negotiations.negotiation_date_end'
            )
        ->join('nvn_negotiations','nvn_negotiations.id_negotiation','=','nvn_negotiations_details.id_negotiation')
        ->join('nvn_quotations','nvn_quotations.id_quotation','=','nvn_negotiations_details.id_quotation')
        ->join('nvn_clients','nvn_clients.id_client','=','nvn_negotiations_details.id_client')
        ->join('nvn_locations','nvn_locations.id_locations','=','nvn_clients.id_city')
        ->join('nvn_dist_channels','nvn_dist_channels.id_channel','=','nvn_clients.id_client_channel')
        ->join('nvn_products','nvn_products.id_product','=','nvn_negotiations_details.id_product')
        ->join('nvn_brands','nvn_brands.id_brand','=','nvn_products.id_brand')
        ->join('nvn_payment_terms','nvn_payment_terms.id_payterms','=','nvn_clients.id_payterm')
        ->join('users as c','c.id','=','nvn_negotiations.created_by')
        ->orderBy('nvn_negotiations.id_negotiation', 'ASC');
        // ->where('nvn_negotiations_details.is_valid', 1);

        if($this->oldReq['user'] != ""){
            $results = $results->where('nvn_negotiations.created_by', $this->oldReq['user']);
        }

        if($this->oldReq['product'] != ""){
            $results = $results->where('nvn_products.id_product', $this->oldReq['product']);
        }

        if ($this->oldReq['desde'] != "") {
            $results = $results->where('nvn_negotiations.negotiation_date_ini', '<=', $this->oldReq['desde'] );
            $results = $results->where('nvn_negotiations.negotiation_date_end', '>=', $this->oldReq['desde'] );
        }

        if ($this->oldReq['hasta'] != "") {
            $results = $results->where('nvn_negotiations.negotiation_date_end', '<=', $this->oldReq['hasta'] );
        }

        if ($this->oldReq['channel'] != "") {
            $results = $results->where('nvn_negotiations.id_channel', $this->oldReq['channel']);
        }

        return $results;
    }

    public function map($results): array
    {
        return [
            $results->negotiation_consecutive,
            $results->quota_consecutive,
            scalename($results->id_product),
            conceptname($results->id_concept),
            clarification($results->aclaracion),
            $results->client_sap_code,
            $results->client_name,
            $results->loc_name,
            statushelp($results->is_authorized, $results->pre_approved, $results->status_id),
            $results->channel_name,
            $results->cam,
            $results->prod_sap_code,
            $results->prod_name,
            $results->brand_name,
            $results->payterm_name,
            $results->quota_value,
            $results->observations,
            $results->discount,
            getNickName($results->id_authorizer_user),
            $results->id_auth_level,
            $results->created_at,
            $results->negotiation_date_ini,
            $results->negotiation_date_end,
        ];
    }
}
