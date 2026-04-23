<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AditionalUses extends Model
{
        //Tabla a negociar

        protected $table = 'nvn_aditional_terms';

        protected $primaryKey = 'id_use';
    
        /**
         * Los atributos que son asignados en masa
         *
         * @var array
         */
        protected $fillable = ['use_name'];
    
}
