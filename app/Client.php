<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_clients';

    protected $primaryKey = 'id_client';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_client_type', 'client_name', 'client_quote_name', 'client_nit', 'prod_invima_reg', 'client_sap_name', 'client_sap_code', 'id_client_channel', 'id_department',
        'id_city', 'client_contact', 'client_phone', 'client_email','client_credit', 'id_diab_contact', 'id_biof_contact', 'created_by',
        'id_payterm','active','client_address','client_position'
    ];


    public function cotizaciones(){
    	return $this->hasMany(Quotation::class,'id_client');
    }

    public function negotations(){
    	return $this->hasMany(Negotiation::class,'id_client');
    }

    public function products(){
    	return $this->hasMany(Product::class);
    }

    public function channel(){
        return $this->belongsTo(Channel_Types::class,'id_client_channel');
    }

    public function type(){
        return $this->belongsTo(Client_Types::class,'id_client_type');
    }

    public function city(){
        return $this->belongsTo(Location::class,'id_city');
    }

    public function department(){
        return $this->belongsTo(Location::class,'id_department');
    }

    public function payterm(){
        return $this->belongsTo(PaymentTerms::class,'id_payterm');
    }

    public function files(){
    	return $this->hasMany(Client_File::class,'id_client');
    }

    public function clientType(){
        return $this->belongsTo(Client_Types::class,'id_client_type');
    }

    public function users(){
        return $this->belongsTo(User::class,'id_diab_contact');
    }

    public static function getClientID($nit){
        $id_client = \DB::table('nvn_clients')
        ->select('id_client')
        ->where('client_nit', '=', $nit)
        ->first('id_client');

        return $id_client->id_client;
    }

    public function sapCodes(){
        return $this->hasMany(Client_Sap_Codes::class,'id_client');
    }

    public function scopeClientChannel($query, $id)
    {
        $query = Client::where('id_client', $id)
        ->with('channel');
        return $query->first()->channel->id_channel;
    }


}
