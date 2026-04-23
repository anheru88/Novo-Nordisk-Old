<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocStatus extends Model
{
   //Tabla a negociar

   protected $table = 'nvn_doc_status';

   protected $primaryKey = 'id_status';

   /**
    * Los atributos que son asignados en masa
    *
    * @var array
    */
   protected $fillable = ['status_name'];
}
