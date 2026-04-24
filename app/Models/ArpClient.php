<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpClient extends Model
{
    protected $fillable = [
        'name',
        'sap_code',
        'id_client_channel',
        'id_client_type',
    ];
}
