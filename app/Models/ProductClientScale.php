<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductClientScale extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'product_id',
        'scale_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function scale()
    {
        return $this->belongsTo(ProductScale::class, 'scale_id', 'id');
    }
}
