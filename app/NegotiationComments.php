<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NegotiationComments extends Model
{
    protected $table = 'nvn_negotiationxcomments';

    protected $primaryKey = 'id_negotiationxcomments';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_negotiation', 'created_by', 'type_comment', 'text_comment'
    ];

    public function negotiation()
	{
		return $this->belongsTo(Negotiation::class, 'id_negotiation');
	}

    public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }



}
