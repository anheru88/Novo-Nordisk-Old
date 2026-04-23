<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesDetails extends Model
{
    protected $table = 'nvn_sales_details';

    protected $primaryKey = 'id_sale_details';

    protected $fillable = ['id_sale_details','id_sales','client_sap_code','id_product', 'scale_number', 'prod_sap_code', 'po_number','payterm_code','brand','billT','bill_doc','bill_number','bill_ltm',
    'bill_date','bill_quanty','bill_price','bill_net_value','bill_real_qty','unitxmaterial','volume','value_mdkk'];


    public function sale(){
        return $this->belongsTo(Scales::class, 'id_sales');
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_sap_code');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'prod_sap_code');
    }

    public function payterm(){
        return $this->belongsTo(PaymentTerms::class, 'payterm_code');
    }
}
