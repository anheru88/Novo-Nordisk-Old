<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArpService extends Model
{
    protected $table = 'nvn_arp_service';
    protected $primaryKey = 'id';
    protected $fillable = ['name','services_arp_id','brand_id','volume','value_cop'];

    public function brands(){
        return $this->hasMany(Brands::class,'id_brand');
    }

    public function service()
    {
        return $this->belongsTo(ServiceArp::class, 'services_arp_id');
    }

    public function scopeBrandsVolume($query, $idService)
    {
        $query = ArpService::where('services_arp_id',$idService)
        ->where('volume','>','0')
        ->where('value_cop','>','0');
        return $query->get('brand_id');
    }

}
