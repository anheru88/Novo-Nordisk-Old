<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client_Types extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_client_types';

    protected $primaryKey = 'id_type';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['type_name'];

    /*public function cliente()
    {
        return $this->belongsTo('App\Client', 'id_client_type', 'id_type');
    }*/

    public function clients(){
        return $this->hasMany(Clients::class);
    }
}
