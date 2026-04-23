<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NegotiationErrors extends Model
{
    protected $table = 'nvn_negotiations_errors';
    protected $primaryKey = 'id_negotiations_errors';
    protected $fillable = ['id_negotiations_errors','id_negotiation_det', 'negotiation_error'];

    public function negotiationDetails()
    {
        return $this->belongsTo(NegotiationDetails::class, 'id_negotiation_det');
    }
}
