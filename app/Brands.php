<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    protected $table = 'nvn_brands';

    protected $primaryKey = 'id_brand';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['brand_name'];

   /* public function cliente_channel()
    {
        return $this->belongsTo('App\Client', 'id_client_channel', 'id_channel');
    }*/

    public function products(){
        return $this->hasMany(Product::class, 'id_brand');
    }

    public function servicesArp(){
        return $this->hasMany(ArpService::class, 'id_brand');
    }

}
