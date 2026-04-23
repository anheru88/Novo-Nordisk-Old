<?php

namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class NegotiationDocs extends Model
{
    protected $table = 'nvn_negotiationxdocs';

    protected $primaryKey = 'id_negotiationxdocs';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_negotiation', 'doc_name'
    ];

    public function negotiation()
	{
		return $this->belongsTo(Negotiation::class, 'id_quotation');
	}


    // Upload and relation QuotationxFiles
    public static function storeFiles($files, $folder){
        $path = public_path() . '/uploads/negotiations/' . $folder;
        File::makeDirectory($path, $mode = 0777, true, true);
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $fileReg = new NegotiationDocs();
            $fileReg->id_negotiation  = $folder;
            $fileReg->file_folder   = '/uploads/negotiations/'.$folder;
            $fileReg->doc_name      = $fileName;
            if ($fileReg->save()) {
                $file->move($path, $fileName);
            }
        }
    }

}
