<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSapCode extends Model
{
    //Tabla a negociar

    protected $primaryKey = 'id_sap_code';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_client',
        'sap_code',
        'client_sap_code',
    ];

    /*public function cliente()
    {
        return $this->belongsTo('App\Client', 'id_client_type', 'id_type');
    }*/

    
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }
}
