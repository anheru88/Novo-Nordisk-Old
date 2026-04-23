<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocFormatCertificate extends Model
{
    protected $table = 'nvn_format_certificates';
    protected $primaryKey = 'id';


    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'country', 'reference', 'header_body',
        'body', 'footer_body', 'user_firm',
        'user_name', 'user_position', 'page_name',
        'footer_column1_1', 'footer_column1_2', 'footer_column1_3',
        'footer_column2_1', 'footer_column3_1', 'active'
    ];
}
