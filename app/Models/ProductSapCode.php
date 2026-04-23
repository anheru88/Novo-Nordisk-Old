<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSapCode extends Model
{
    protected $primaryKey = 'id_product_sapcode';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_product',
        'active',
        'sap_code',
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }
}
