<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormatCertificate extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'formattype_id',
        'country',
        'reference',
        'header_body',
        'body',
        'footer_body',
        'user_firm',
        'user_name',
        'user_position',
        'page_name',
        'footer_column1_1',
        'footer_column1_2',
        'footer_column1_3',
        'footer_column2_1',
        'footer_column3_1',
        'active',
    ];

    public function formattype()
    {
        return $this->belongsTo(DocFormatType::class, 'formattype_id', 'id');
    }
}
