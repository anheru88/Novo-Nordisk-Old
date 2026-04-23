<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceArp extends Model
{
    protected $table = 'nvn_services_arp';
    protected $primaryKey = 'id';
    protected $fillable = ['name','arps_id'];


    public function servicesData(){
        return $this->hasMany(ArpService::class, 'services_arp_id');
    }


}
