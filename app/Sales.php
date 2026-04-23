<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sales extends Model
{
    //
    protected $table = 'nvn_sales';

    protected $primaryKey = 'id_sales';

    protected $fillable = ['doc_name'];


    public static function ncEscalas($idSale)
	{
		/*$app = DB::table('nvn_negotiations_details')
            ->select(
                'users.nickname',
                'nvn_clients.client_sap_code',
                'nvn_clients.client_name',
                'nvn_brands.brand_name',
                'nvn_products.prod_name',
                'nvn_sales_details.bill_price',
                'nvn_sales_details.bill_net_value',
                'nvn_negotiations_details.id_negotiation_det',
                'nvn_sales_details.bill_number'
            )
            ->join('nvn_negotiations', 'nvn_negotiations.id_negotiation', '=', 'nvn_negotiations_details.id_negotiation')
            ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_negotiations_details.id_product')
            ->join('nvn_brands', 'nvn_brands.id_brand', '=', 'nvn_products.id_brand')
			//->join('nvn_payment_terms', 'nvn_payment_terms.id_payterms', '=', 'nvn_negotiation_details.id_payterm')
            ->join('nvn_clients', 'nvn_clients.id_client', '=', 'nvn_negotiations_details.id_client')
            ->join('users', 'users.id', '=', 'nvn_negotiations.created_by')
            ->leftJoin('nvn_sales_details', function($join){
                $join->on('nvn_sales_details.prod_sap_code','=','nvn_products.prod_sap_code');
                $join->on('nvn_sales_details.client_sap_code','=','nvn_clients.client_sap_code');
            })
            ->join('nvn_product_scales', 'nvn_product_scales.id_product', '=', 'nvn_products.id_product')
			->where('id_concept', '=', 0)
			->where('is_valid', '=', 1)
            ->orderBy('nvn_clients.client_name', 'ASC')
            ->groupBy('nvn_products.prod_name','users.nickname')
            //->distinct()

            ->get(['nvn_negotiations_details.id_product'])
            ->sum('nvn_sales_details.bill_real_qty');
*/
        $app = DB::table('nvn_sales_details')
            ->join('nvn_products', 'nvn_products.prod_sap_code', '=', 'nvn_sales_details.prod_sap_code')
            ->join('nvn_brands', 'nvn_brands.id_brand', '=', 'nvn_products.id_brand')
			//->join('nvn_payment_terms', 'nvn_payment_terms.id_payterms', '=', 'nvn_negotiation_details.id_payterm')
            ->join('nvn_clients', 'nvn_clients.client_sap_code', '=', 'nvn_sales_details.client_sap_code')
            ->leftJoin('nvn_negotiations_details', function($join){
                $join->on('nvn_negotiations_details.id_product','=','nvn_products.id_product');
                $join->on('nvn_negotiations_details.id_client','=','nvn_clients.id_client');
                $join->where('nvn_negotiations_details.id_concept', '=', 0);
                $join->where('nvn_negotiations_details.is_valid', '=', 6);
                //$join->where('nvn_negotiations_details.authlevel', '=', 2);
            })
             ->leftJoin('nvn_productxclientxscales',function($join){
                $join->on('nvn_productxclientxscales.id_product','=','nvn_products.id_product');
                $join->on('nvn_productxclientxscales.id_client','=','nvn_clients.id_client');
            })
            ->leftJoin('nvn_product_scales_level',function($join){
                $join->on('nvn_product_scales_level.id_scale','=','nvn_productxclientxscales.id_scale');
                $join->where(DB::raw('CAST(nvn_product_scales_level.scale_min AS integer)','<=','qty'));
            })
            ->join('nvn_negotiations', 'nvn_negotiations.id_negotiation', '=', 'nvn_negotiations_details.id_negotiation')
            ->join('users', 'users.id', '=', 'nvn_negotiations.created_by')
            ->select(
                //'users.nickname',
                'nvn_sales_details.client_sap_code',
                'nvn_clients.client_name',
                /*'nvn_brands.brand_name',*/
                'nvn_sales_details.prod_sap_code',
                'nvn_products.prod_name',
                DB::raw('SUM(nvn_sales_details.bill_real_qty) as total'),
                DB::raw('SUM(nvn_sales_details.bill_price) as price'),
                DB::raw('SUM(nvn_sales_details.bill_net_value) as net'),
                DB::raw('SUM(nvn_sales_details.volume) as volumen')
               /* 'nvn_negotiations_details.id_negotiation_det',
                'nvn_negotiations.id_negotiation',
                'nvn_negotiations.negotiation_consecutive'*/
            )
            /*->join('users', 'users.id', '=', 'nvn_negotiations.created_by')
            ->join('nvn_product_scales', 'nvn_product_scales.id_product', '=', 'nvn_products.id_product')*/
			/*->where('id_concept', '=', 0)
            ->where('is_valid', '=', 1)*/
            ->groupBy(
                'nvn_sales_details.client_sap_code',
                'nvn_clients.client_name',
                'nvn_sales_details.prod_sap_code',
                /*'nvn_brands.brand_name'*/
                'nvn_products.prod_name'
                //'users.nickname',
                )
            //->orderBy('nvn_clients.client_name', 'ASC')
            /*->groupBy('nvn_products.prod_name','nvn_clients.client_sap_code','nvn_clients.client_name',
            'nvn_brands.brand_name','nvn_sales_details.bill_price','nvn_sales_details.bill_net_value',
            'nvn_negotiations_details.id_negotiation_det','nvn_sales_details.bill_number')*/
            //->distinct()
            ->get();


		if (!empty($app)) {
			return $app;
		} else {
			$app[] = "";
			return $app;
		}
	}

}
