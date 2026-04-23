<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    //Tabla a negociar

    protected $primaryKey = 'id_product';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_product_h',
        'modification_type',
        'comments',
        'v_institutional_price',
        'v_commercial_price',
        'prod_valid_date_ini',
        'prod_valid_date_end',
    ];

    public function productH()
    {
        return $this->belongsTo(Product::class, 'id_product_h', 'id_product');
    }
}
