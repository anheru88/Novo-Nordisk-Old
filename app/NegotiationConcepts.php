<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NegotiationConcepts extends Model
{
    protected $table = 'nvn_negotiation_concepts';

    protected $primaryKey = 'id_negotiation_concepts';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'type_concept', 'concept_percentaje','sap_concept','name_concept'
    ];


    public function negotiationDetails(){
        return $this->belongsTo(NegotiationDetails::class, 'id_negotiation_concepts');
    }
}
