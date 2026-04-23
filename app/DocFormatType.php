<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocFormatType extends Model
{
    protected $table = 'nvn_doc_format_types';
    protected $primaryKey = 'id_formattype';


     /**
    * Los atributos que son asignados en masa
    *
    * @var array
    */
    protected $fillable = ['format_name'];
}
