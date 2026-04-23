<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountLevels extends Model
{
    protected $table = 'nvn_discount_levels';

    protected $primaryKey = 'id_disc_level';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['disc_level_name'];

}


