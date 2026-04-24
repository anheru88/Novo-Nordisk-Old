<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arp extends Model
{
    protected $fillable = [
        'name',
        'year',
        'month_avr',
        'std',
    ];

    public function arpBusinessCase()
    {
        return $this->hasMany(ArpBusinessCase::class, 'arp_id', 'id');
    }

    public function servicesArp()
    {
        return $this->hasMany(ArpService::class, 'arps_id', 'id');
    }
}
