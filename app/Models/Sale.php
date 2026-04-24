<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    //
    protected $fillable = [
        'name',
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
                'negotiation_details.id',
                'sale_details.bill_number'
            )
            ->join('negotiations', 'negotiations.id', '=', 'negotiation_details.negotiation_id')
            ->join('products', 'products.id', '=', 'negotiation_details.product_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            //->join('payment_terms', 'payment_terms.id', '=', 'negotiation_details.id_payterm')
            ->join('clients', 'clients.id', '=', 'negotiation_details.client_id')
            ->join('users', 'users.id', '=', 'negotiations.created_by')
            ->leftJoin('sale_details', function($join){
                $join->on('sale_details.prod_sap_code','=','products.prod_sap_code');
                $join->on('sale_details.client_sap_code','=','clients.client_sap_code');
            })
            ->join('product_scales', 'product_scales.product_id', '=', 'products.id')
            ->where('id_concept', '=', 0)
            ->where('is_valid', '=', 1)
            ->orderBy('clients.client_name', 'ASC')
            ->groupBy('products.prod_name','users.nickname')
            //->distinct()

            ->get(['negotiation_details.product_id'])
            ->sum('sale_details.bill_real_qty');
*/
        $app = DB::table('sale_details')
            ->join('products', 'products.sap_code', '=', 'sale_details.prod_sap_code')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            // ->join('payment_terms', 'payment_terms.id', '=', 'negotiation_details.id_payterm')
            ->join('clients', 'clients.sap_code', '=', 'sale_details.client_sap_code')
            ->leftJoin('negotiation_details', function ($join) {
                $join->on('negotiation_details.product_id', '=', 'products.id');
                $join->on('negotiation_details.client_id', '=', 'clients.id');
                $join->where('negotiation_details.concept_id', '=', 0);
                $join->where('negotiation_details.is_valid', '=', 6);
                // $join->where('negotiation_details.authlevel', '=', 2);
            })
            ->leftJoin('product_client_scales', function ($join) {
                $join->on('product_client_scales.product_id', '=', 'products.id');
                $join->on('product_client_scales.client_id', '=', 'clients.id');
            })
            ->leftJoin('product_scale_levels', function ($join) {
                $join->on('product_scale_levels.scale_id', '=', 'product_client_scales.scale_id');
                $join->where(DB::raw('CAST(product_scale_levels.scale_min AS integer)', '<=', 'qty'));
            })
            ->join('negotiations', 'negotiations.id', '=', 'negotiation_details.negotiation_id')
            ->join('users', 'users.id', '=', 'negotiations.created_by')
            ->select(
                // 'users.nickname',
                'sale_details.client_sap_code',
                'clients.name',
                /* 'brands.name', */
                'sale_details.prod_sap_code',
                'products.name',
                DB::raw('SUM(sale_details.bill_real_qty) as total'),
                DB::raw('SUM(sale_details.bill_price) as price'),
                DB::raw('SUM(sale_details.bill_net_value) as net'),
                DB::raw('SUM(sale_details.volume) as volumen')
                /* 'negotiation_details.id',
                 'negotiations.id',
                 'negotiations.negotiation_consecutive'*/
            )
            /*->join('users', 'users.id', '=', 'negotiations.created_by')
            ->join('product_scales', 'product_scales.product_id', '=', 'products.id')*/
            /*->where('id_concept', '=', 0)
            ->where('is_valid', '=', 1)*/
            ->groupBy(
                'sale_details.client_sap_code',
                'clients.name',
                'sale_details.prod_sap_code',
                /* 'brands.name' */
                'products.name'
                // 'users.nickname',
            )
            // ->orderBy('clients.name', 'ASC')
            /*->groupBy('products.name','clients.client_sap_code','clients.name',
            'brands.name','sale_details.bill_price','sale_details.bill_net_value',
            'negotiation_details.id','sale_details.bill_number')*/
            // ->distinct()
            ->get();

        if (! empty($app)) {
            return $app;
        } else {
            $app[] = '';

            return $app;
        }
    }

    public function salesDetails()
    {
        return $this->hasMany(SaleDetail::class, 'sales_id', 'id');
    }
}
