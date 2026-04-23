<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductScaleLevel extends Model
{
    //Tabla a negociar

    protected $primaryKey = 'id_scale_level';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_scale',
        'scale_discount',
        'scale_min',
        'scale_max',
        'version',
        'id_measure_unit',
    ];

    
    public function measureUnit()
    {
        return $this->belongsTo(ProductMeasureUnit::class, 'id_measure_unit', 'id_unit');
    }

    public function scale()
    {
        return $this->belongsTo(ProductScale::class, 'id_scale', 'id_scale');
    }
}
