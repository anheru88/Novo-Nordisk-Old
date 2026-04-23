<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocFormatType extends Model
{
    protected $primaryKey = 'id_formattype';


     /**
    * Los atributos que son asignados en masa
    *
    * @var array
    */
    protected $fillable = [
        'format_name',
    ];

    public function docFormats()
    {
        return $this->hasMany(DocFormat::class, 'id_formattype', 'id_formattype');
    }

    public function formatCertificates()
    {
        return $this->hasMany(FormatCertificate::class, 'id_formattype', 'id_formattype');
    }
}
