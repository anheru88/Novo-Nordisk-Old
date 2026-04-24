<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationApprover extends Model
{
    protected $fillable = [
        'answer',
        'quotation_id',
        'user_id',
    ];

    // Una aprobación tiene muchos usuarios "aprobadores"

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }
}
