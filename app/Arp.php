<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arp extends Model
{
    protected $table = 'nvn_arps';
    protected $primaryKey = 'id';
    protected $fillable = ['name','year','std','month_avr'];

    public function services(){
        return $this->hasMany(ServiceArp::class,'arps_id');
    }

    public function pbc()
    {
        return $this->hasOne(BusinessArp::class, 'arp_id');
    }
}
