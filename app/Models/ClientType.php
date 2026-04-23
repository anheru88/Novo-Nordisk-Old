<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientType extends Model
{
    //Tabla a negociar

    protected $primaryKey = 'id_type';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'type_name',
    ];

    /*public function cliente()
    {
        return $this->belongsTo('App\Client', 'id_client_type', 'id_type');
    }*/

    
    public function clients()
    {
        return $this->hasMany(Client::class, 'id_client_type', 'id_type');
    }
}
