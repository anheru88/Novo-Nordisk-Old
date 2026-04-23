<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NegotiationApprovers extends Model
{
    protected $table = 'nvn_negotiationxapprovers';

    protected $primaryKey = 'id';

    protected $fillable = ['id', 'answer', 'negotiation_id', 'user_id'];


    // Una aprobación tiene muchos usuarios "aprobadores"

    public function approversUser(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function negotiation_status(){
        return $this->hasOne(NegotiationxStatus::class);
    }

    public function negotiation(){
        return $this->belongsTo(Negotiation::class, 'negotiation_id');
    }
}
