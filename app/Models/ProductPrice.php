<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'pricelists_id',
        'prod_sap_code',
        'v_institutional_price',
        'v_commercial_price',
        'increment_max',
        'version',
        'active',
        'valid_date_ini',
        'valid_date_end',
        'comments',
    ];

    public function pricelists()
    {
        return $this->belongsTo(PriceList::class, 'pricelists_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
