<?php

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;

class ProductScale extends Model
{
    //Tabla a negociar

    protected $primaryKey = 'id_scale';
    protected $fillable = [
        'id_product',
        'scale_number',
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function productxclientxscales()
    {
        return $this->hasMany(ProductClientScale::class, 'id_scale', 'id_scale');
    }

    public function productScalesLevel()
    {
        return $this->hasMany(ProductScaleLevel::class, 'id_scale', 'id_scale');
    }
}
