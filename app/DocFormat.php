<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocFormat extends Model
{
    protected $table = 'nvn_doc_formats';
    protected $primaryKey = 'id_format';


    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['name', 'body', 'conditions_time', 'conditions_content', 'conditions_special', 'terms_title', 'terms_content', 'sign_name', 'sign_charge', 'sign_image', 'footer', 'active'];
}
