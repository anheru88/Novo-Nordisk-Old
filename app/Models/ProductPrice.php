<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $primaryKey = 'id_productxprices';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_product',
        'id_pricelists',
        'prod_sap_code',
        'v_institutional_price',
        'v_commercial_price',
        'prod_increment_max',
        'version',
        'active',
        'prod_valid_date_ini',
        'prod_valid_date_end',
        'comments',
    ];

    
    public function pricelists()
    {
        return $this->belongsTo(PriceList::class, 'id_pricelists', 'id_pricelists');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }
}
