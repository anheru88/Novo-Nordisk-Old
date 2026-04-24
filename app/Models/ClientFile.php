<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientFile extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'folder',
        'name',
        'size',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
