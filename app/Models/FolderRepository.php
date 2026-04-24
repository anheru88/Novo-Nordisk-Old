<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolderRepository extends Model
{
    protected $table = 'folder_repository';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'id_parent',
    ];

    public function docRepository()
    {
        return $this->hasMany(DocRepository::class, 'folder_id', 'id');
    }
}
