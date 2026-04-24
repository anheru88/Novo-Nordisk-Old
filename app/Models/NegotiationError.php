<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegotiationError extends Model
{
    protected $fillable = [
        'negotiation_det_id',
        'error',
    ];

    public function negotiationDet()
    {
        return $this->belongsTo(NegotiationDetail::class, 'negotiation_det_id', 'id');
    }
}
