<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class QuotationDoc extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'quotation_id',
        'name',
        'folder',
    ];

    // Upload and relation QuotationxFiles
    public static function storeFiles($files, $folder)
    {
        $path = public_path().'/uploads/quotations/'.$folder;
        File::makeDirectory($path, $mode = 0777, true, true);
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $fileReg = new QuotationDoc;
            $fileReg->quotation_id = $folder;
            $fileReg->folder = '/uploads/quotations/'.$folder;
            $fileReg->name = $fileName;
            if ($fileReg->save()) {
                $file->move($path, $fileName);
            }
        }
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }
}
