<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FolderRepository extends Model
{
    protected $table = 'nvn_folder_repository';
    protected $primaryKey = 'id_folder';


     /**
    * Los atributos que son asignados en masa
    *
    * @var array
    */
    protected $fillable = ['folder_name','folder_url','id_parent'];

    public function documents(){
        return $this->hasMany(DocRepository::class,'id_folder');
    }
}
