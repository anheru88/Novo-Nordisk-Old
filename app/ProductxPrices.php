<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductxPrices extends Model
{
    protected $table = 'nvn_productxprices';

    protected $primaryKey = 'id_productxprices';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['id_product','id_pricelists','prod_sap_code','v_institutional_price','v_commercial_price','prod_increment_max','prod_valid_date_ini','prod_valid_date_end'];

    public function product(){
      return $this->belongsTo(Product::class,'id_product');
    }

    public function list(){
      return $this->belongsTo(PricesList::class,'id_pricelists');
    }
}
