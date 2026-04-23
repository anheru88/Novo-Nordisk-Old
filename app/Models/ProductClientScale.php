<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductClientScale extends Model
{
    protected $primaryKey = 'id_productxclient';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_client',
        'id_product',
        'id_scale',
    ];

    
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function scale()
    {
        return $this->belongsTo(ProductScale::class, 'id_scale', 'id_scale');
    }
}
