<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocRepository extends Model
{
    protected $table = 'doc_repository';
    protected $primaryKey = 'id_doc';


     /**
    * Los atributos que son asignados en masa
    *
    * @var array
    */
    protected $fillable = [
        'doc_name',
        'id_folder',
    ];

    
    public function folder()
    {
        return $this->belongsTo(FolderRepository::class, 'id_folder', 'id_folder');
    }
}
