<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegotiationConcept extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'concept_percentage',
        'concept_compress',
        'sap_concept',
    ];
}
