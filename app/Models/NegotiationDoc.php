<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class NegotiationDoc extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'negotiation_id',
        'name',
        'folder',
    ];

    // Upload and relation QuotationxFiles
    public static function storeFiles($files, $folder)
    {
        $path = public_path().'/uploads/negotiations/'.$folder;
        File::makeDirectory($path, $mode = 0777, true, true);
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $fileReg = new NegotiationDoc;
            $fileReg->negotiation_id = $folder;
            $fileReg->folder = '/uploads/negotiations/'.$folder;
            $fileReg->name = $fileName;
            if ($fileReg->save()) {
                $file->move($path, $fileName);
            }
        }
    }

    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class, 'negotiation_id', 'id');
    }
}
