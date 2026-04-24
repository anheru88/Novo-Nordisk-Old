<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpDiscount extends Model
{
    protected $fillable = [
        'name',
        'discount_percentage',
        'discount_units',
        'discount_clients',
        'discount_products',
        'discount_months',
        'discount_value',
    ];
}
