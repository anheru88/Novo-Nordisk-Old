<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegotiationError extends Model
{
    protected $primaryKey = 'id_negotiations_errors';
    protected $fillable = [
        'id_negotiation_det',
        'negotiation_error',
    ];

    
    public function negotiationDet()
    {
        return $this->belongsTo(NegotiationDetail::class, 'id_negotiation_det', 'id_negotiation_det');
    }
}
