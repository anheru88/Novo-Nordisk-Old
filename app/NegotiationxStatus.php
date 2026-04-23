<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NegotiationxStatus extends Model
{
    protected $table = 'nvn_negotiationxstatus';
    protected $primaryKey = 'id';
    protected $fillable = ['status_id', 'user_id', 'negotiation_id'];

    public function status(){
        return $this->hasOne(Status::class, 'status_id');
    }
}
