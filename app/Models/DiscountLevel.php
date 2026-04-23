<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountLevel extends Model
{
    protected $primaryKey = 'id_disc_level';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'disc_level_name',
    ];


    public function productAuthLevels()
    {
        return $this->hasMany(ProductAuthLevel::class, 'id_level_discount', 'id_disc_level');
    }
}


