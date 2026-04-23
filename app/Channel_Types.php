<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel_Types extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_dist_channels';

    protected $primaryKey = 'id_channel';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['channel_name'];

   /* public function cliente_channel()
    {
        return $this->belongsTo('App\Client', 'id_client_channel', 'id_channel');
    }*/

    public function clients(){
        return $this->hasMany(Clients::class);
    }

    public function quotation(){
        return $this->hasMany(Quotation::class,'id_channel');
    }


}
