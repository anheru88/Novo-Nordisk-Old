<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScalesLevels extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_product_scales_level';

    protected $primaryKey = 'id_scale_level';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['id_scale', 'scale_discount', 'scale_min', 'scale_max', 'id_measure_unit'];

    public function scale()
    {
        return $this->belongsTo(Scales::class, 'id_scale');
    }

    public function measureUnit()
    {
        return $this->belongsTo(MeasureUnit::class, 'id_measure_unit');
    }
}
