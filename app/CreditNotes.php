<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditNotes extends Model
{
    //
    protected $table = 'nvn_credit_notes';

    protected $primaryKey = 'id_credit_notes';

    protected $fillable = ['doc_name'];


    public function products(){
    	return $this->hasMany(CreditNotesClients::class,'id_credit_notes');
    }

    public function productsBills(){
    	return $this->hasMany(CreditNotesClientsBills::class,'id_credit_notes');
    }

}
