<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepoFile extends Model
{
    protected $primaryKey = 'id_files';
    public $incrementing = false;

    protected $fillable = [
        'id_files',
        'id_parent',
        'file_folder',
        'file_name',
    ];
}
