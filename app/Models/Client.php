<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    // Tabla a negociar

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'client_type_id',
        'client_name',
        'client_quote_name',
        'client_nit',
        'client_sap_name',
        'client_sap_code',
        'client_channel_id',
        'department_id',
        'city_id',
        'client_contact',
        'client_phone',
        'client_email',
        'client_credit',
        'diab_contact_id',
        'biof_contact_id',
        'created_by',
        'client_address',
        'client_position',
        'client_area_code',
        'active',
        'payterm_id',
        'client_email_secondary',
    ];

    public static function getClientID($nit)
    {
        $client = \DB::table('clients')
            ->select('id')
            ->where('client_nit', '=', $nit)
            ->first('id');

        return $client->id;
    }

    public function scopeClientChannel($query, $id)
    {
        $query = Client::where('id', $id)
            ->with('channel');

        return $query->first()->channel->id;
    }

    public function city()
    {
        return $this->belongsTo(Location::class, 'city_id', 'id');
    }

    public function clientChannel()
    {
        return $this->belongsTo(DistChannel::class, 'client_channel_id', 'id');
    }

    public function clientType()
    {
        return $this->belongsTo(ClientType::class, 'client_type_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Location::class, 'department_id', 'id');
    }

    public function payterm()
    {
        return $this->belongsTo(PaymentTerm::class, 'payterm_id', 'id');
    }

    public function productxclientxscales()
    {
        return $this->hasMany(ProductClientScale::class, 'client_id', 'id');
    }

    public function negotiationsDetails()
    {
        return $this->hasMany(NegotiationDetail::class, 'client_id', 'id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'client_id', 'id');
    }

    public function quotationsDetails()
    {
        return $this->hasMany(QuotationDetail::class, 'client_id', 'id');
    }

    public function clientsSapCodes()
    {
        return $this->hasMany(ClientSapCode::class, 'client_id', 'id');
    }

    public function arpSimulationsDetails()
    {
        return $this->hasMany(ArpSimulationDetail::class, 'client_id', 'id');
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class, 'client_id', 'id');
    }

    public function clientsFiles()
    {
        return $this->hasMany(ClientFile::class, 'client_id', 'id');
    }
}
