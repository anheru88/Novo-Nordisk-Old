<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationComment extends Model
{
    protected $primaryKey = 'id_quotationxcomments';

    protected $fillable = [
        'id_quotation',
        'created_by',
        'type_comment',
        'text_comment',
    ];


    
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'id_quotation', 'id_quotation');
    }
}
