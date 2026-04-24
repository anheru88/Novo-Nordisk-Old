<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSapCode extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'active',
        'sap_code',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
