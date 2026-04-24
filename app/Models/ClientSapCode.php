<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSapCode extends Model
{
    // Tabla a negociar

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'sap_code',
        'client_sap_code',
    ];

    /*public function cliente()
    {
        return $this->belongsTo('App\Client', 'client_type_id', 'id');
    }*/

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
