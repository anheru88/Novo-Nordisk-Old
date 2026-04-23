<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAuthLevel extends Model
{
    
    protected $primaryKey = 'id_level';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_product',
        'id_dist_channel',
        'id_level_discount',
        'discount_value',
        'version',
        'active',
        'id_pricelists',
        'discount_price',
    ];
    
    
    public function distChannel()
    {
        return $this->belongsTo(DistChannel::class, 'id_dist_channel', 'id_channel');
    }

    public function levelDiscount()
    {
        return $this->belongsTo(DiscountLevel::class, 'id_level_discount', 'id_disc_level');
    }

    public function pricelists()
    {
        return $this->belongsTo(PriceList::class, 'id_pricelists', 'id_pricelists');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function negotiationsDetails()
    {
        return $this->hasMany(NegotiationDetail::class, 'id_prod_auth_level', 'id_level');
    }

    public function quotationsDetails()
    {
        return $this->hasMany(QuotationDetail::class, 'id_prod_auth_level', 'id_level');
    }
}
