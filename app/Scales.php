<?php

namespace App;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;

class Scales extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_product_scales';
    protected $primaryKey = 'id_scale';
    protected $fillable = ['id_product', 'scale_number', 'id_channel'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function scalelvl()
    {
        return $this->hasMany(ScalesLevels::class, 'id_scale');
    }

    public function channel(){
        return $this->belongsTo(Channel::class, 'id_channel');
    }
}
