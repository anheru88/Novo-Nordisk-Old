<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeasureUnit extends Model
{
    protected $table = 'nvn_product_measure_units';

    protected $primaryKey = 'id_unit';

    protected $fillable = ['id_unit','unit_name'];

    public function products(){
        return $this->hasMany(Product::class);
    }


}
