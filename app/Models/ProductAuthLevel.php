<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAuthLevel extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'dist_channel_id',
        'level_discount_id',
        'discount_value',
        'version',
        'active',
        'pricelists_id',
        'discount_price',
    ];

    public function distChannel()
    {
        return $this->belongsTo(DistChannel::class, 'dist_channel_id', 'id');
    }

    public function levelDiscount()
    {
        return $this->belongsTo(DiscountLevel::class, 'level_discount_id', 'id');
    }

    public function pricelists()
    {
        return $this->belongsTo(PriceList::class, 'pricelists_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function negotiationsDetails()
    {
        return $this->hasMany(NegotiationDetail::class, 'prod_auth_level_id', 'id');
    }

    public function quotationsDetails()
    {
        return $this->hasMany(QuotationDetail::class, 'prod_auth_level_id', 'id');
    }
}
