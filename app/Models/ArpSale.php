<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpSale extends Model
{
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
        'sales_list_id',
    ];
}
