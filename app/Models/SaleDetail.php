<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $primaryKey = 'id_sale_details';

    protected $fillable = [
        'id_sales',
        'client_sap_code',
        'prod_sap_code',
        'po_number',
        'payterm_code',
        'brand',
        'bill_doc',
        'bill_number',
        'bill_ltm',
        'bill_date',
        'bill_quanty',
        'bill_price',
        'bill_net_value',
        'bill_real_qty',
        'unitxmaterial',
        'volume',
        'value_mdkk',
    ];


    
    public function sales()
    {
        return $this->belongsTo(Sale::class, 'id_sales', 'id_sales');
    }
}
