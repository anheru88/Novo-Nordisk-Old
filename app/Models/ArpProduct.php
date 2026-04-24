<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpProduct extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'min_price',
        'conversion',
        'prod_sap_code',
    ];
}
