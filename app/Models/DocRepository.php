<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocRepository extends Model
{
    protected $table = 'doc_repository';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'doc_name',
        'folder_id',
        'size',
    ];

    public function folder()
    {
        return $this->belongsTo(FolderRepository::class, 'folder_id', 'id');
    }
}
