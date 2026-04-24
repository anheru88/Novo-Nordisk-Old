<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductScaleLevel extends Model
{
    // Tabla a negociar

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'scale_id',
        'scale_discount',
        'scale_min',
        'scale_max',
        'version',
        'measure_unit_id',
    ];

    public function measureUnit()
    {
        return $this->belongsTo(ProductMeasureUnit::class, 'measure_unit_id', 'id');
    }

    public function scale()
    {
        return $this->belongsTo(ProductScale::class, 'scale_id', 'id');
    }
}
