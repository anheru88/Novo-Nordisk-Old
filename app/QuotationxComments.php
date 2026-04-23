<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationxComments extends Model
{
    protected $table = 'nvn_quotationxcomments';
    protected $primaryKey = 'id_quotationxcomments';

    protected $fillable = ['id_quotation','type_comment','text_comment','created_by'];


    public function quotation()
	{
		return $this->belongsTo(Quotation::class, 'id_quotation');
	}

    public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
