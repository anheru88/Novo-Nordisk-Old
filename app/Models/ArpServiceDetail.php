<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpServiceDetail extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'services_arp_id',
        'brand_id',
        'volume',
        'value_cop',
    ];

    public function scopeBrandsVolume($query, $idService)
    {
        $query = ArpServiceDetail::where('services_arp_id',$idService)
        ->where('volume','>','0')
        ->where('value_cop','>','0');
        return $query->get('brand_id');
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id_brand');
    }

    public function servicesArp()
    {
        return $this->belongsTo(ArpService::class, 'services_arp_id', 'id');
    }
}
