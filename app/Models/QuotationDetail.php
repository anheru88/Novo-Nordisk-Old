<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    protected $fillable = [
        'quotation_id',
        'client_id',
        'product_id',
        'payterm_id',
        'quantity',
        'prod_cost',
        'time_discount',
        'pay_discount',
        'price_uminima',
        'price_discount',
        'prod_auth_level_id',
        'authlevel',
        'is_valid',
    ];

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $guarded = [];

    public static function getProducts($id_quota)
    {
        $app = \DB::table('quotation_details')
            ->select('quotation_details.id', 'quotation_details.product_id', 'quotation_details.payterm_id', 'quotation_details.quantity', 'quotation_details.time_discount',
                'quotation_details.pay_discount', 'quotation_details.price_uminima', 'quotation_details.totalValue', 'quotation_details.prod_auth_level_id', 'quotation_details.is_valid', 'products.prod_name',
                'products.v_institutional_price', 'products.v_commercial_price', 'payment_terms.payterm_name')
            ->join('products', 'products.id', '=', 'quotation_details.product_id')
            ->join('payment_terms', 'payment_terms.id', '=', 'quotation_details.payterm_id')
            ->where('quotation_id', '=', $id_quota)
            ->orderBy('id')
            ->get();

        return $app;
    }

    public static function getProductsAll($id_client)
    {
        $app = \DB::table('quotation_details')
            ->select(
                'quotation_details.id',
                'quotation_details.product_id',
                'quotation_details.quotation_id',
                'quotation_details.payterm_id',
                'quotation_details.quantity',
                'quotation_details.prod_cost',
                'quotation_details.time_discount',
                'quotation_details.pay_discount',
                'quotation_details.price_uminima',
                'quotation_details.totalValue',
                'quotation_details.prod_auth_level_id',
                'quotation_details.is_valid',
                'products.prod_name',
                'products.v_institutional_price',
                'products.v_commercial_price',
                'payment_terms.payterm_name'
            )
            ->join('products', 'products.id', '=', 'quotation_details.product_id')
            ->join('payment_terms', 'payment_terms.id', '=', 'quotation_details.payterm_id')
            ->join('product_scales', 'product_scales.product_id', '=', 'products.id')
            ->where('client_id', '=', $id_client)
            ->where(function ($query) {
                $query->where('quotation_details.is_valid', '=', 6)
                    ->orWhere('quotation_details.is_valid', '=', 1);
            })
            ->orderBy('products.prod_name', 'ASC')
            ->distinct()
            ->get(['quotation_details.product_id']);

        if (! empty($app)) {
            return $app;
        } else {
            $app[] = '';

            return $app;
        }
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function payterm()
    {
        return $this->belongsTo(PaymentTerm::class, 'payterm_id', 'id');
    }

    public function prodAuthLevel()
    {
        return $this->belongsTo(ProductAuthLevel::class, 'prod_auth_level_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }
}
