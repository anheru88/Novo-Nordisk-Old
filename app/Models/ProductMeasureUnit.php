<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMeasureUnit extends Model
{
    protected $primaryKey = 'id_unit';

    protected $fillable = [
        'unit_name',
    ];

    
    public function productScalesLevel()
    {
        return $this->hasMany(ProductScaleLevel::class, 'id_measure_unit', 'id_unit');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_measure_unit', 'id_unit');
    }
}
