<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_Sap_Codes extends Model
{
    protected $table = 'nvn_product_sap_codes';

    protected $primaryKey = 'id_product_sapcode';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['id_product', 'sap_code'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
