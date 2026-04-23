<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_Line extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_product_lines';

    protected $primaryKey = 'id_line';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['type_name'];

    
    public function products(){
        return $this->hasMany(Product::class);
    }
    
}
