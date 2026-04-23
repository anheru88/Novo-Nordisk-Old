<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class NegotiationDoc extends Model
{
    protected $primaryKey = 'id_negotiationxdocs';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_negotiation',
        'doc_name',
        'file_folder',
    ];

    // Upload and relation QuotationxFiles
    public static function storeFiles($files, $folder){
        $path = public_path() . '/uploads/negotiations/' . $folder;
        File::makeDirectory($path, $mode = 0777, true, true);
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $fileReg = new NegotiationDoc();
            $fileReg->id_negotiation  = $folder;
            $fileReg->file_folder   = '/uploads/negotiations/'.$folder;
            $fileReg->doc_name      = $fileName;
            if ($fileReg->save()) {
                $file->move($path, $fileName);
            }
        }
    }


    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class, 'id_negotiation', 'id_negotiation');
    }
}
