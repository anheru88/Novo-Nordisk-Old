<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocStatus extends Model
{
   //Tabla a negociar

   protected $table = 'doc_status';

   protected $primaryKey = 'id_status';

   /**
    * Los atributos que son asignados en masa
    *
    * @var array
    */
   protected $fillable = [
        'status_name',
        'status_color',
    ];
}
