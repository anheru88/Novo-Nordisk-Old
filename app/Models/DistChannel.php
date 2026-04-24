<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistChannel extends Model
{
    // Tabla a negociar

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

    public function clients()
    {
        return $this->hasMany(Client::class, 'client_channel_id', 'id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'channel_id', 'id');
    }

    public function productAuthLevels()
    {
        return $this->hasMany(ProductAuthLevel::class, 'dist_channel_id', 'id');
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class, 'channel_id', 'id');
    }
}
