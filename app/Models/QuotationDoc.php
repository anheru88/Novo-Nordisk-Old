<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class QuotationDoc extends Model
{
    protected $primaryKey = 'id_quotationxdoc';


     /**
    * Los atributos que son asignados en masa
    *
    * @var array
    */
    protected $fillable = [
        'id_quotation',
        'doc_name',
        'file_folder',
    ];

    // Upload and relation QuotationxFiles
    public static function storeFiles($files, $folder){
        $path = public_path() . '/uploads/quotations/' . $folder;
        File::makeDirectory($path, $mode = 0777, true, true);
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $fileReg = new QuotationDoc();
            $fileReg->id_quotation  = $folder;
            $fileReg->file_folder   = '/uploads/quotations/' . $folder;
            $fileReg->doc_name      = $fileName;
            if ($fileReg->save()) {
                $file->move($path, $fileName);
            }
        }
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'id_quotation', 'id_quotation');
    }
}

