<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'authorizer_user_id',
        'version',
        'active',
        'comments',
        'name',
    ];

    public function authorizer()
    {
        return $this->belongsTo(User::class, 'authorizer_user_id', 'id');
    }

    public function productAuthLevels()
    {
        return $this->hasMany(ProductAuthLevel::class, 'pricelists_id', 'id');
    }

    public function productxprices()
    {
        return $this->hasMany(ProductPrice::class, 'pricelists_id', 'id');
    }
}
