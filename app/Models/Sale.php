<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    //
    protected $primaryKey = 'id_sales';

    protected $fillable = [
        'doc_name',
    ];


    public static function ncEscalas($idSale)
	{
		/*$app = DB::table('negotiation_details')
            ->select(
                'users.nickname',
                'clients.client_sap_code',
                'clients.client_name',
                'brands.brand_name',
                'products.prod_name',
                'sale_details.bill_price',
                'sale_details.bill_net_value',
                'negotiation_details.id_negotiation_det',
                'sale_details.bill_number'
            )
            ->join('negotiations', 'negotiations.id_negotiation', '=', 'negotiation_details.id_negotiation')
            ->join('products', 'products.id_product', '=', 'negotiation_details.id_product')
            ->join('brands', 'brands.id_brand', '=', 'products.id_brand')
			//->join('payment_terms', 'payment_terms.id_payterms', '=', 'negotiation_details.id_payterm')
            ->join('clients', 'clients.id_client', '=', 'negotiation_details.id_client')
            ->join('users', 'users.id', '=', 'negotiations.created_by')
            ->leftJoin('sale_details', function($join){
                $join->on('sale_details.prod_sap_code','=','products.prod_sap_code');
                $join->on('sale_details.client_sap_code','=','clients.client_sap_code');
            })
            ->join('product_scales', 'product_scales.id_product', '=', 'products.id_product')
			->where('id_concept', '=', 0)
			->where('is_valid', '=', 1)
            ->orderBy('clients.client_name', 'ASC')
            ->groupBy('products.prod_name','users.nickname')
            //->distinct()

            ->get(['negotiation_details.id_product'])
            ->sum('sale_details.bill_real_qty');
*/
        $app = DB::table('sale_details')
            ->join('products', 'products.prod_sap_code', '=', 'sale_details.prod_sap_code')
            ->join('brands', 'brands.id_brand', '=', 'products.id_brand')
			//->join('payment_terms', 'payment_terms.id_payterms', '=', 'negotiation_details.id_payterm')
            ->join('clients', 'clients.client_sap_code', '=', 'sale_details.client_sap_code')
            ->leftJoin('negotiation_details', function($join){
                $join->on('negotiation_details.id_product','=','products.id_product');
                $join->on('negotiation_details.id_client','=','clients.id_client');
                $join->where('negotiation_details.id_concept', '=', 0);
                $join->where('negotiation_details.is_valid', '=', 6);
                //$join->where('negotiation_details.authlevel', '=', 2);
            })
             ->leftJoin('product_client_scales',function($join){
                $join->on('product_client_scales.id_product','=','products.id_product');
                $join->on('product_client_scales.id_client','=','clients.id_client');
            })
            ->leftJoin('product_scale_levels',function($join){
                $join->on('product_scale_levels.id_scale','=','product_client_scales.id_scale');
                $join->where(DB::raw('CAST(product_scale_levels.scale_min AS integer)','<=','qty'));
            })
            ->join('negotiations', 'negotiations.id_negotiation', '=', 'negotiation_details.id_negotiation')
            ->join('users', 'users.id', '=', 'negotiations.created_by')
            ->select(
                //'users.nickname',
                'sale_details.client_sap_code',
                'clients.client_name',
                /*'brands.brand_name',*/
                'sale_details.prod_sap_code',
                'products.prod_name',
                DB::raw('SUM(sale_details.bill_real_qty) as total'),
                DB::raw('SUM(sale_details.bill_price) as price'),
                DB::raw('SUM(sale_details.bill_net_value) as net'),
                DB::raw('SUM(sale_details.volume) as volumen')
               /* 'negotiation_details.id_negotiation_det',
                'negotiations.id_negotiation',
                'negotiations.negotiation_consecutive'*/
            )
            /*->join('users', 'users.id', '=', 'negotiations.created_by')
            ->join('product_scales', 'product_scales.id_product', '=', 'products.id_product')*/
			/*->where('id_concept', '=', 0)
            ->where('is_valid', '=', 1)*/
            ->groupBy(
                'sale_details.client_sap_code',
                'clients.client_name',
                'sale_details.prod_sap_code',
                /*'brands.brand_name'*/
                'products.prod_name'
                //'users.nickname',
                )
            //->orderBy('clients.client_name', 'ASC')
            /*->groupBy('products.prod_name','clients.client_sap_code','clients.client_name',
            'brands.brand_name','sale_details.bill_price','sale_details.bill_net_value',
            'negotiation_details.id_negotiation_det','sale_details.bill_number')*/
            //->distinct()
            ->get();


		if (!empty($app)) {
			return $app;
		} else {
			$app[] = "";
			return $app;
		}
	}


    public function salesDetails()
    {
        return $this->hasMany(SaleDetail::class, 'id_sales', 'id_sales');
    }
}
