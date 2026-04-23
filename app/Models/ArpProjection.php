<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpProjection extends Model
{
    protected $primaryKey = 'id_projection';

    protected $fillable = [
        'projection_name',
        'projection_description',
        'projection_file',
        'data',
    ];
}
