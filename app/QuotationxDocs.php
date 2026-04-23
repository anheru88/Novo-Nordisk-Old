<?php

namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class QuotationxDocs extends Model
{
    protected $table = 'nvn_quotationxdocs';
    protected $primaryKey = 'id_quotationxdoc';


     /**
    * Los atributos que son asignados en masa
    *
    * @var array
    */
    protected $fillable = ['id_quotation','doc_name'];

    public function quotation()
	{
		return $this->belongsTo(Quotation::class, 'id_quotation');
	}


    // Upload and relation QuotationxFiles
    public static function storeFiles($files, $folder){
        $path = public_path() . '/uploads/quotations/' . $folder;
        File::makeDirectory($path, $mode = 0777, true, true);
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $fileReg = new QuotationxDocs();
            $fileReg->id_quotation  = $folder;
            $fileReg->file_folder   = '/uploads/quotations/' . $folder;
            $fileReg->doc_name      = $fileName;
            if ($fileReg->save()) {
                $file->move($path, $fileName);
            }
        }
    }
}

