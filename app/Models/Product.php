<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    // Tabla a negociar

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sap_code',
        'commercial_name',
        'generic_name',
        'invima_reg',
        'cum',
        'prod_line_id',
        'package',
        'package_unit',
        'measure_unit_id',
        'is_regulated',
        'obesidad',
        'insumo',
        'v_institutional_price',
        'v_commercial_price',
        'valid_date_ini',
        'valid_date_end',
        'created_by',
        'increment_max',
        'renovacion',
        'comments',
        'extension_time',
        'material',
        'aditional_use',
        'status',
        'concentration',
        'brand_id',
        'commercial_unit',
        'arp_divide',
    ];

    public static function getProductsWithLevels($id_pricelist)
    {
        $app = DB::table('product_prices')
            ->select('product_prices.product_id', 'product_prices.v_institutional_price', 'product_prices.v_commercial_price', 'product_prices.increment_max',
                'product_prices.valid_date_ini', 'product_prices.valid_date_end', 'product_auth_levels.dist_channel_id', 'product_auth_levels.level_discount_id',
                'product_auth_levels.discount_value', 'product_auth_levels.product_id', 'products.name')
            ->join('product_auth_levels', 'product_auth_levels.pricelists_id', '=', 'product_prices.pricelists_id')
            ->join('products', 'products.id', '=', 'product_prices.product_id')
            ->where('product_prices.pricelists_id', '=', $id_pricelist)
            ->groupBy('product_prices.product_id', 'product_auth_levels.product_id')
            ->orderBy('products.name')
            ->get();

        return $app;
    }

    public function scopeArpDivision($id)
    {
        return $id;
    }

    public function measureUnit()
    {
        return $this->belongsTo(ProductMeasureUnit::class, 'measure_unit_id', 'id');
    }

    public function prodLine()
    {
        return $this->belongsTo(ProductLine::class, 'prod_line_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function productxclientxscales()
    {
        return $this->hasMany(ProductClientScale::class, 'product_id', 'id');
    }

    public function productScales()
    {
        return $this->hasMany(ProductScale::class, 'product_id', 'id');
    }

    public function productsH()
    {
        return $this->hasMany(ProductHistory::class, 'product_h_id', 'id');
    }

    public function negotiationsDetails()
    {
        return $this->hasMany(NegotiationDetail::class, 'product_id', 'id');
    }

    public function quotationsDetails()
    {
        return $this->hasMany(QuotationDetail::class, 'product_id', 'id');
    }

    public function productSapCodes()
    {
        return $this->hasMany(ProductSapCode::class, 'product_id', 'id');
    }

    public function productAuthLevels()
    {
        return $this->hasMany(ProductAuthLevel::class, 'product_id', 'id');
    }

    public function arpSimulationsDetails()
    {
        return $this->hasMany(ArpSimulationDetail::class, 'product_id', 'id');
    }

    public function productxprices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }
}
