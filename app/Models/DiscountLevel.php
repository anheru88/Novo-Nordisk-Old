<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountLevel extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function productAuthLevels()
    {
        return $this->hasMany(ProductAuthLevel::class, 'level_discount_id', 'id');
    }
}
