<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
	
    protected $fillable = [
        'id_quotation',
        'id_client',
        'id_product',
        'id_payterm',
        'quantity',
        'prod_cost',
        'time_discount',
        'pay_discount',
        'price_uminima',
        'price_discount',
        'id_prod_auth_level',
        'authlevel',
        'is_valid',
    ];
	protected $primaryKey = 'id_quota_det';

	/**
	 * Los atributos que son asignados en masa
	 *
	 * @var array
	 */
	protected $guarded = [ ];

	public static function getProducts($id_quota)
	{
		$app = \DB::table('quotation_details')
			->select('quotation_details.id_quota_det', 'quotation_details.id_product', 'quotation_details.id_payterm', 'quotation_details.quantity', 'quotation_details.time_discount',
			'quotation_details.pay_discount', 'quotation_details.price_uminima', 'quotation_details.totalValue', 'quotation_details.id_prod_auth_level', 'quotation_details.is_valid','products.prod_name',
			'products.v_institutional_price', 'products.v_commercial_price', 'payment_terms.payterm_name')
			->join('products', 'products.id_product', '=', 'quotation_details.id_product')
			->join('payment_terms', 'payment_terms.id_payterms', '=', 'quotation_details.id_payterm')
			->where('id_quotation', '=', $id_quota)
			->orderBy('id_quota_det')
			->get();

		return $app;
	}

	public static function getProductsAll($id_client)
	{
		$app = \DB::table('quotation_details')
			->select(
				'quotation_details.id_quota_det',
				'quotation_details.id_product',
                'quotation_details.id_quotation',
				'quotation_details.id_payterm',
				'quotation_details.quantity',
				'quotation_details.prod_cost',
				'quotation_details.time_discount',
				'quotation_details.pay_discount',
				'quotation_details.price_uminima',
				'quotation_details.totalValue',
				'quotation_details.id_prod_auth_level',
                'quotation_details.is_valid',
				'products.prod_name',
				'products.v_institutional_price',
				'products.v_commercial_price',
				'payment_terms.payterm_name'
			)
			->join('products', 'products.id_product', '=', 'quotation_details.id_product')
			->join('payment_terms', 'payment_terms.id_payterms', '=', 'quotation_details.id_payterm')
			->join('product_scales', 'product_scales.id_product', '=', 'products.id_product')
			->where('id_client', '=', $id_client)
            ->where(function($query){
                $query->where('quotation_details.is_valid', '=', 6)
                ->orWhere('quotation_details.is_valid', '=', 1);
            })
			->orderBy('products.prod_name', 'ASC')
			->distinct()
			->get(['quotation_details.id_product']);

		if (!empty($app)) {
			return $app;
		} else {
			$app[] = "";
			return $app;
		}
	}

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function payterm()
    {
        return $this->belongsTo(PaymentTerm::class, 'id_payterm', 'id_payterms');
    }

    public function prodAuthLevel()
    {
        return $this->belongsTo(ProductAuthLevel::class, 'id_prod_auth_level', 'id_level');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'id_quotation', 'id_quotation');
    }
}
