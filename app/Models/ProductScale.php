<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductScale extends Model
{
    // Tabla a negociar

    protected $fillable = [
        'product_id',
        'scale_number',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productxclientxscales()
    {
        return $this->hasMany(ProductClientScale::class, 'scale_id', 'id');
    }

    public function productScalesLevel()
    {
        return $this->hasMany(ProductScaleLevel::class, 'scale_id', 'id');
    }
}
