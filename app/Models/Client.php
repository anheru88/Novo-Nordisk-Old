<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //Tabla a negociar

    protected $primaryKey = 'id_client';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_client_type',
        'client_name',
        'client_quote_name',
        'client_nit',
        'client_sap_name',
        'client_sap_code',
        'id_client_channel',
        'id_department',
        'id_city',
        'client_contact',
        'client_phone',
        'client_email',
        'client_credit',
        'id_diab_contact',
        'id_biof_contact',
        'created_by',
        'client_address',
        'client_position',
        'client_area_code',
        'active',
        'id_payterm',
        'client_email_secondary',
    ];


    public static function getClientID($nit){
        $id_client = \DB::table('clients')
        ->select('id_client')
        ->where('client_nit', '=', $nit)
        ->first('id_client');

        return $id_client->id_client;
    }

    public function scopeClientChannel($query, $id)
    {
        $query = Client::where('id_client', $id)
        ->with('channel');
        return $query->first()->channel->id_channel;
    }



    public function city()
    {
        return $this->belongsTo(Location::class, 'id_city', 'id_locations');
    }

    public function clientChannel()
    {
        return $this->belongsTo(DistChannel::class, 'id_client_channel', 'id_channel');
    }

    public function clientType()
    {
        return $this->belongsTo(ClientType::class, 'id_client_type', 'id_type');
    }

    public function department()
    {
        return $this->belongsTo(Location::class, 'id_department', 'id_locations');
    }

    public function payterm()
    {
        return $this->belongsTo(PaymentTerm::class, 'id_payterm', 'id_payterms');
    }

    public function productxclientxscales()
    {
        return $this->hasMany(ProductClientScale::class, 'id_client', 'id_client');
    }

    public function negotiationsDetails()
    {
        return $this->hasMany(NegotiationDetail::class, 'id_client', 'id_client');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'id_client', 'id_client');
    }

    public function quotationsDetails()
    {
        return $this->hasMany(QuotationDetail::class, 'id_client', 'id_client');
    }

    public function clientsSapCodes()
    {
        return $this->hasMany(ClientSapCode::class, 'id_client', 'id_client');
    }

    public function arpSimulationsDetails()
    {
        return $this->hasMany(ArpSimulationDetail::class, 'client_id', 'id_client');
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class, 'id_client', 'id_client');
    }

    public function clientsFiles()
    {
        return $this->hasMany(ClientFile::class, 'id_client', 'id_client');
    }
}
