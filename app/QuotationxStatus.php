<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationxStatus extends Model
{
           /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'nvn_quotationxstatus';
    protected $primaryKey = 'id';
    protected $fillable = ['status_id', 'user_id', 'quotation_id'];

    public function status(){
        return $this->hasOne(Status::class, 'status_id');
    }

}
