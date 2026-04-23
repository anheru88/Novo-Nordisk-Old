<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientFile extends Model
{
    protected $primaryKey = 'id_files';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_client',
        'file_folder',
        'file_name',
    ];

    
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }
}
