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
        'prod_name',
        'prod_sap_code',
        'prod_commercial_name',
        'prod_generic_name',
        'prod_invima_reg',
        'prod_cum',
        'prod_line_id',
        'prod_package',
        'prod_package_unit',
        'measure_unit_id',
        'is_prod_regulated',
        'prod_obesidad',
        'prod_insumo',
        'v_institutional_price',
        'v_commercial_price',
        'prod_valid_date_ini',
        'prod_valid_date_end',
        'created_by',
        'prod_increment_max',
        'renovacion',
        'comments',
        'extension_time',
        'material',
        'aditional_use',
        'status',
        'prod_concentration',
        'brand_id',
        'prod_commercial_unit',
        'arp_divide',
    ];

    public static function getProductsWithLevels($id_pricelist)
    {
        $app = DB::table('product_prices')
            ->select('product_prices.product_id', 'product_prices.v_institutional_price', 'product_prices.v_commercial_price', 'product_prices.prod_increment_max',
                'product_prices.prod_valid_date_ini', 'product_prices.prod_valid_date_end', 'product_auth_levels.dist_channel_id', 'product_auth_levels.level_discount_id',
                'product_auth_levels.discount_value', 'product_auth_levels.product_id', 'products.prod_name')
            ->join('product_auth_levels', 'product_auth_levels.pricelists_id', '=', 'product_prices.pricelists_id')
            ->join('products', 'products.id', '=', 'product_prices.product_id')
            ->where('product_prices.pricelists_id', '=', $id_pricelist)
            ->groupBy('product_prices.product_id', 'product_auth_levels.product_id')
            ->orderBy('products.prod_name')
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
