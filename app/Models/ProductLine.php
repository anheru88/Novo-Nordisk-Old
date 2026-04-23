<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLine extends Model
{
    //Tabla a negociar

    protected $primaryKey = 'id_line';

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
        return $this->hasMany(Product::class, 'id_prod_line', 'id_line');
    }
}
