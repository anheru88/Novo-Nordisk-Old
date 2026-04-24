<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpSimulation extends Model
{
    protected $fillable = [
        'name',
    ];

    // Relations

    public function arpSimulationsDetails()
    {
        return $this->hasMany(ArpSimulationDetail::class, 'arp_simulation_id', 'id');
    }
}
