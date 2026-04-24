<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpBusinessCase extends Model
{
    protected $table = 'arp_business_case';

    protected $fillable = [
        'arp_id',
        'brand_id',
        'pbc',
        'service_arp_id',
    ];

    public function arp()
    {
        return $this->belongsTo(Arp::class, 'arp_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
