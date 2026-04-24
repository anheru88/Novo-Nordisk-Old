<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /* public function cliente_channel()
     {
         return $this->belongsTo('App\Client', 'client_channel_id', 'id');
     }*/

    public function arpBusinessCase()
    {
        return $this->hasMany(ArpBusinessCase::class, 'brand_id', 'id');
    }

    public function arpService()
    {
        return $this->hasMany(ArpServiceDetail::class, 'brand_id', 'id');
    }

    public function arpSimulationsDetails()
    {
        return $this->hasMany(ArpSimulationDetail::class, 'brand_id', 'id');
    }
}
