<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegotiationStatusChange extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'status_id',
        'user_id',
        'negotiation_id',
    ];

    
    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class, 'negotiation_id', 'id_negotiation');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'status_id');
    }
}
