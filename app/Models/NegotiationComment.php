<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegotiationComment extends Model
{
    protected $primaryKey = 'id_negotiationxcomments';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_negotiation',
        'created_by',
        'type_comment',
        'text_comment',
    ];

    
    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class, 'id_negotiation', 'id_negotiation');
    }
}
