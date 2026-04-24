<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMeasureUnit extends Model
{
    protected $fillable = [
        'unit_name',
    ];

    public function productScalesLevel()
    {
        return $this->hasMany(ProductScaleLevel::class, 'measure_unit_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'measure_unit_id', 'id');
    }
}
