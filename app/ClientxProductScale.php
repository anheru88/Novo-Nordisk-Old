<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientxProductScale extends Model
{
    protected $table = 'nvn_productxclientxscales';

    protected $primaryKey = 'id_productxclient';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['id_client','id_product','id_scale'];

    public function product(){
        return $this->belongsTo(Product::class,'id_product');
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'id_client');
    }

    public function scale()
    {
        return $this->belongsTo(Scales::class,'id_scale');
    }
}
