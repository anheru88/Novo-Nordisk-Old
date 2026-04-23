<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
        //Tabla a negociar

        protected $table = 'nvn_status';

        protected $primaryKey = 'status_id';

        /**
         * Los atributos que son asignados en masa
         *
         * @var array
         */
        protected $fillable = [
            'status_id', 'status_name', 'status_color'
        ];


        public function ScopeGetName($query,$status_id)
        {
            return $query->where('status_id', $status_id);
        }
}
