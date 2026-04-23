<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegotiationApprover extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'answer',
        'negotiation_id',
        'user_id',
    ];


    // Una aprobación tiene muchos usuarios "aprobadores"

    
    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class, 'negotiation_id', 'id_negotiation');
    }
}
