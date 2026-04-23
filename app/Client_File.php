<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client_File extends Model
{
    protected $table = 'nvn_clients_files';

    protected $primaryKey = 'id_files';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['id_client','file_folder','file_name'];

    public function clients(){
        return $this->belongsTo(Client::class,'id_client');
    }


}
