<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_products';

    protected $primaryKey = 'id_product';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'prod_name', 'prod_sap_code', 'prod_commercial_name', 'prod_generic_name', 'prod_invima_reg', 'prod_cum', 'id_prod_line', 'prod_package', 'prod_package_unit',
        'id_measure_unit', 'is_prod_regulated','prod_obesidad','prod_insumo','v_institutional_price', 'v_commercial_price', 'prod_valid_date_ini', 'prod_valid_date_end',
         'created_by','material','aditional_use', 'prod_increment_max', 'renovacion','comments', 'extension_time','id_brand'
    ];


    public function client(){
    	return $this->belongsTo(Client::class);
    }

    public function brand(){
        return $this->belongsTo(Brands::class,'id_brand');
    }

    public function productLine(){
        return $this->belongsTo(Product_Line::class,'id_prod_line');
    }

    public function measureUnit(){
        return $this->belongsTo(MeasureUnit::class,'id_measure_unit');
    }

    public function aditionalUse(){
        return $this->belongsTo(AditionalUses::class,'id_use');
    }

    public function quotaDetails(){
        return $this->hasMany(QuotationDetails::class);
    }

    public function prices(){
        return $this->hasMany(ProductxPrices::class,'id_product');
    }

    public function sapCodes(){
        return $this->hasMany(Product_Sap_Codes::class,'id_product');
    }

    public static function getProductsWithLevels($id_pricelist)
	{
		$app = DB::table('nvn_productxprices')
            ->select('nvn_productxprices.id_product', 'nvn_productxprices.v_institutional_price', 'nvn_productxprices.v_commercial_price', 'nvn_productxprices.prod_increment_max',
            'nvn_productxprices.prod_valid_date_ini','nvn_productxprices.prod_valid_date_end','nvn_product_auth_levels.id_dist_channel', 'nvn_product_auth_levels.id_level_discount',
            'nvn_product_auth_levels.discount_value','nvn_product_auth_levels.id_product','nvn_products.prod_name')
			->join('nvn_product_auth_levels', 'nvn_product_auth_levels.id_pricelists', '=', 'nvn_productxprices.id_pricelists')
			->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_productxprices.id_product')
            ->where('nvn_productxprices.id_pricelists', '=', $id_pricelist)
            ->groupBy('nvn_productxprices.id_product','nvn_product_auth_levels.id_product')
			->orderBy('nvn_products.prod_name')
			->get();

		return $app;
	}

    public function scopeArpDivision($id)
    {
        return $id;
    }

}
