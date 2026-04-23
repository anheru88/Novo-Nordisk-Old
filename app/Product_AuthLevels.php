<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_AuthLevels extends Model
{
    
    protected $table = 'nvn_product_auth_levels';

    protected $primaryKey = 'id_level';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['id_level','id_product','id_dist_channel','id_level_discount','discount_value','discount_price'];
    
    public function product(){
        return $this->belongsTo(Product::class,'id_product');
    }

    public function channel(){
        return $this->belongsTo(Channel_Types::class,'id_dist_channel');
    }

    public function leveldiscount(){
        return $this->belongsTo(DiscountLevels::class,'id_level_discount');
    }


}
