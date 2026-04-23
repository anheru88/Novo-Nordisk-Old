<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpProduct extends Model
{
    protected $primaryKey = 'id_product';
    public $incrementing = false;

    protected $fillable = [
        'id_product',
        'min_price',
        'conversion',
        'prod_sap_code',
    ];
}
