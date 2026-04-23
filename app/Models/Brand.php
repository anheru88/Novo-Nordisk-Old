<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $primaryKey = 'id_brand';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'brand_name',
    ];

   /* public function cliente_channel()
    {
        return $this->belongsTo('App\Client', 'id_client_channel', 'id_channel');
    }*/

    
    public function arpBusinessCase()
    {
        return $this->hasMany(ArpBusinessCase::class, 'brand_id', 'id_brand');
    }

    public function arpService()
    {
        return $this->hasMany(ArpServiceDetail::class, 'brand_id', 'id_brand');
    }

    public function arpSimulationsDetails()
    {
        return $this->hasMany(ArpSimulationDetail::class, 'brand_id', 'id_brand');
    }
}
