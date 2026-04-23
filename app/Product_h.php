<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_h extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_products_h';

    protected $primaryKey = 'id_product';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_product_h', 'modification_type', 'comments', 'v_institutional_price', 'v_commercial_price', 'prod_valid_date_ini', 'prod_valid_date_end'
    ];
}
