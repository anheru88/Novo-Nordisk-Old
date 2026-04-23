<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArpSimulations extends Model
{
    protected $table = 'nvn_arp_simulations';
    protected $primaryKey = 'id';
    protected $fillable = ['simulation_name'];

    //Relations
    public function details(){
        return $this->hasMany(ArpSimulationDetail::class);
    }
}
