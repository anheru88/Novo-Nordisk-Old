<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegotiationComment extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'negotiation_id',
        'created_by',
        'type_comment',
        'text_comment',
    ];

    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class, 'negotiation_id', 'id');
    }
}
