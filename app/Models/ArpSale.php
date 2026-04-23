<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpSale extends Model
{
    protected $primaryKey = 'id_sale';

    protected $fillable = [
        'bill_number',
        'bill_quanty',
        'bill_price',
        'bill_net_value',
        'bill_real_qty',
        'bill_date',
        'client_sap_code',
        'prod_sap_code',
        'brand',
        'id_sales_list',
    ];
}
