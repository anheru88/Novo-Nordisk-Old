<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocFormatType extends Model
{
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
        return $this->hasMany(DocFormat::class, 'formattype_id', 'id');
    }

    public function formatCertificates()
    {
        return $this->hasMany(FormatCertificate::class, 'formattype_id', 'id');
    }
}
