<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    //Tabla a negociar

    protected $primaryKey = 'id_product';

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
        'id_prod_line',
        'prod_package',
        'prod_package_unit',
        'id_measure_unit',
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
        'id_brand',
        'prod_commercial_unit',
        'arp_divide',
    ];


    public static function getProductsWithLevels($id_pricelist)
	{
		$app = DB::table('product_prices')
            ->select('product_prices.id_product', 'product_prices.v_institutional_price', 'product_prices.v_commercial_price', 'product_prices.prod_increment_max',
            'product_prices.prod_valid_date_ini','product_prices.prod_valid_date_end','product_auth_levels.id_dist_channel', 'product_auth_levels.id_level_discount',
            'product_auth_levels.discount_value','product_auth_levels.id_product','products.prod_name')
			->join('product_auth_levels', 'product_auth_levels.id_pricelists', '=', 'product_prices.id_pricelists')
			->join('products', 'products.id_product', '=', 'product_prices.id_product')
            ->where('product_prices.id_pricelists', '=', $id_pricelist)
            ->groupBy('product_prices.id_product','product_auth_levels.id_product')
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
        return $this->belongsTo(ProductMeasureUnit::class, 'id_measure_unit', 'id_unit');
    }

    public function prodLine()
    {
        return $this->belongsTo(ProductLine::class, 'id_prod_line', 'id_line');
    }

    public function productxclientxscales()
    {
        return $this->hasMany(ProductClientScale::class, 'id_product', 'id_product');
    }

    public function productScales()
    {
        return $this->hasMany(ProductScale::class, 'id_product', 'id_product');
    }

    public function productsH()
    {
        return $this->hasMany(ProductHistory::class, 'id_product_h', 'id_product');
    }

    public function negotiationsDetails()
    {
        return $this->hasMany(NegotiationDetail::class, 'id_product', 'id_product');
    }

    public function quotationsDetails()
    {
        return $this->hasMany(QuotationDetail::class, 'id_product', 'id_product');
    }

    public function productSapCodes()
    {
        return $this->hasMany(ProductSapCode::class, 'id_product', 'id_product');
    }

    public function productAuthLevels()
    {
        return $this->hasMany(ProductAuthLevel::class, 'id_product', 'id_product');
    }

    public function arpSimulationsDetails()
    {
        return $this->hasMany(ArpSimulationDetail::class, 'product_id', 'id_product');
    }

    public function productxprices()
    {
        return $this->hasMany(ProductPrice::class, 'id_product', 'id_product');
    }
}
