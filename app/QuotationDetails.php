<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationDetails extends Model
{
	protected $table = 'nvn_quotations_details';

	protected $primaryKey = 'id_quota_det';

	/**
	 * Los atributos que son asignados en masa
	 *
	 * @var array
	 */
	protected $guarded = [ ];

	public function quotation()
	{
		return $this->belongsTo(Quotation::class, 'id_quotation');
	}

	public function product()
	{
		return $this->belongsTo(Product::class, 'id_product');
	}

	public function payterm()
	{
		return $this->belongsTo(PaymentTerms::class, 'id_payterm');
    }

    public function productAuthlvl()
    {
        return $this->belongsTo(Product_AuthLevels::class,'id_prod_auth_level');
    }

    public function client()
	{
		return $this->belongsTo(Client::class, 'id_client');
	}


	public static function getProducts($id_quota)
	{
		$app = \DB::table('nvn_quotations_details')
			->select('nvn_quotations_details.id_quota_det', 'nvn_quotations_details.id_product', 'nvn_quotations_details.id_payterm', 'nvn_quotations_details.quantity', 'nvn_quotations_details.time_discount',
			'nvn_quotations_details.pay_discount', 'nvn_quotations_details.price_uminima', 'nvn_quotations_details.totalValue', 'nvn_quotations_details.id_prod_auth_level', 'nvn_quotations_details.is_valid','nvn_products.prod_name',
			'nvn_products.v_institutional_price', 'nvn_products.v_commercial_price', 'nvn_payment_terms.payterm_name')
			->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_quotations_details.id_product')
			->join('nvn_payment_terms', 'nvn_payment_terms.id_payterms', '=', 'nvn_quotations_details.id_payterm')
			->where('id_quotation', '=', $id_quota)
			->orderBy('id_quota_det')
			->get();

		return $app;
	}

	public static function getProductsAll($id_client)
	{
		$app = \DB::table('nvn_quotations_details')
			->select(
				'nvn_quotations_details.id_quota_det',
				'nvn_quotations_details.id_product',
                'nvn_quotations_details.id_quotation',
				'nvn_quotations_details.id_payterm',
				'nvn_quotations_details.quantity',
				'nvn_quotations_details.prod_cost',
				'nvn_quotations_details.time_discount',
				'nvn_quotations_details.pay_discount',
				'nvn_quotations_details.price_uminima',
				'nvn_quotations_details.totalValue',
				'nvn_quotations_details.id_prod_auth_level',
                'nvn_quotations_details.is_valid',
				'nvn_products.prod_name',
				'nvn_products.v_institutional_price',
				'nvn_products.v_commercial_price',
				'nvn_payment_terms.payterm_name'
			)
			->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_quotations_details.id_product')
			->join('nvn_payment_terms', 'nvn_payment_terms.id_payterms', '=', 'nvn_quotations_details.id_payterm')
			->join('nvn_product_scales', 'nvn_product_scales.id_product', '=', 'nvn_products.id_product')
			->where('id_client', '=', $id_client)
            ->where(function($query){
                $query->where('nvn_quotations_details.is_valid', '=', 6)
                ->orWhere('nvn_quotations_details.is_valid', '=', 1);
            })
			->orderBy('nvn_products.prod_name', 'ASC')
			->distinct()
			->get(['nvn_quotations_details.id_product']);

		if (!empty($app)) {
			return $app;
		} else {
			$app[] = "";
			return $app;
		}
	}
}
