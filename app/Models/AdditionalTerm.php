<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalTerm extends Model
{
        //Tabla a negociar

        protected $primaryKey = 'id_use';
    
        /**
         * Los atributos que son asignados en masa
         *
         * @var array
         */
        protected $fillable = [
        'use_name',
    ];
    
}
