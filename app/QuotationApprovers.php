<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationApprovers extends Model
{

    protected $table = 'nvn_quotation_approvers';

    protected $primaryKey = 'id';

    protected $fillable = ['id', 'answer', 'quotation_id', 'user_id'];


    // Una aprobación tiene muchos usuarios "aprobadores"

    public function approversUser(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function quotation_status(){
        return $this->hasOne(Quotationxstatus::class);
    }

    public function quotation(){
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
}
