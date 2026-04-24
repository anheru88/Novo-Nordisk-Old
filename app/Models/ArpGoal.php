<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpGoal extends Model
{
    protected $fillable = [
        'prod_sap_code',
        'name',
        'quantity',
        'value',
        'quantity_com',
        'value_com',
        'quantity_ins',
        'value_ins',
    ];
}
