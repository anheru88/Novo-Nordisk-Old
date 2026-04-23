<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationStatusChange extends Model
{
           /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $primaryKey = 'id';
    protected $fillable = [
        'status_id',
        'user_id',
        'quotation_id',
    ];

    
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id_quotation');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'status_id');
    }
}
