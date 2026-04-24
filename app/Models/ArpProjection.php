<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpProjection extends Model
{
    protected $fillable = [
        'name',
        'description',
        'file',
        'data',
    ];
}
