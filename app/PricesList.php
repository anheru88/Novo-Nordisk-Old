<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PricesList extends Model
{
    protected $table = 'nvn_priceslists';

    protected $primaryKey = 'id_pricelists';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['list_version','active'];

    public function products(){
      return $this->hasMany(ProductxPrices::class,'id_pricelists');
    }
}
