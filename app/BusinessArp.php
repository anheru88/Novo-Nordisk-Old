<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessArp extends Model
{
    protected $table = 'nvn_arp_business_case';

    protected $fillable = ['service_arp_id','brand_id','pbc'];


    public function service(){
        return $this->belongsTo(ServiceArp::class, 'service_arp_id');
    }

    public function products(){
        return $this->hasMany(Product::class, 'id_brand');
    }

}
