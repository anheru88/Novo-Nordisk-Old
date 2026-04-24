<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpClient extends Model
{
    protected $fillable = [
        'client_name',
        'client_sap_code',
        'id_client_channel',
        'id_client_type',
    ];
}
