<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client_Sap_Codes extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_clients_sap_codes';

    protected $primaryKey = 'id_sap_ode';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['id_client', 'sap_code'];

    /*public function cliente()
    {
        return $this->belongsTo('App\Client', 'id_client_type', 'id_type');
    }*/

    public function clients(){
        return $this->hasMany(Clients::class);
    }
}
