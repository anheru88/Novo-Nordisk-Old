<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistChannel extends Model
{
    //Tabla a negociar

    protected $primaryKey = 'id_channel';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'channel_name',
    ];

   /* public function cliente_channel()
    {
        return $this->belongsTo('App\Client', 'id_client_channel', 'id_channel');
    }*/

    
    public function clients()
    {
        return $this->hasMany(Client::class, 'id_client_channel', 'id_channel');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'id_channel', 'id_channel');
    }

    public function productAuthLevels()
    {
        return $this->hasMany(ProductAuthLevel::class, 'id_dist_channel', 'id_channel');
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class, 'id_channel', 'id_channel');
    }
}
