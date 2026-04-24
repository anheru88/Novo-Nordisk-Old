<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLine extends Model
{
    // Tabla a negociar

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'line_name',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'prod_line_id', 'id');
    }
}
