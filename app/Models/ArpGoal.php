<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpGoal extends Model
{
    protected $primaryKey = 'id_goal';

    protected $fillable = [
        'prod_sap_code',
        'goal_name',
        'goal_quantity',
        'goal_value',
        'goal_quantity_com',
        'goal_value_com',
        'goal_quantity_ins',
        'goal_value_ins',
    ];
}
