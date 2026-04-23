<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocFormat extends Model
{
    protected $primaryKey = 'id_format';


    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_formattype',
        'name',
        'body',
        'conditions_time',
        'conditions_content',
        'conditions_special',
        'terms_title',
        'terms_content',
        'sign_name',
        'sign_charge',
        'sign_image',
        'footer',
        'active',
    ];

    public function formattype()
    {
        return $this->belongsTo(DocFormatType::class, 'id_formattype', 'id_formattype');
    }
}
