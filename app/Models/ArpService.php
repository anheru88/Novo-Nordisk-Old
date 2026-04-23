<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpService extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'arps_id',
    ];

    public function arp()
    {
        return $this->belongsTo(Arp::class, 'arps_id', 'id');
    }

    public function arpServiceDetails()
    {
        return $this->hasMany(ArpServiceDetail::class, 'services_arp_id', 'id');
    }
}
