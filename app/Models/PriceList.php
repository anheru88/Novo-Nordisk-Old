<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $primaryKey = 'id_pricelists';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_authorizer_user',
        'list_version',
        'active',
        'comments',
        'list_name',
    ];

    
    public function productAuthLevels()
    {
        return $this->hasMany(ProductAuthLevel::class, 'id_pricelists', 'id_pricelists');
    }

    public function productxprices()
    {
        return $this->hasMany(ProductPrice::class, 'id_pricelists', 'id_pricelists');
    }
}
