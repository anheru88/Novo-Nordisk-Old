<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalTerm extends Model
{
    // Tabla a negociar

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
