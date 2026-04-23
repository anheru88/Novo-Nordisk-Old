<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditNotesClientsBills extends Model
{
    //
    protected $table = 'nvn_credit_notes_clients_bills';

    protected $primaryKey = 'id_credit_notes_clients_b';

    protected $fillable = ['id_credit_notes_clients_b','client_sap_code','concept','bill','id_credit_notes'];


    public function details(){
    	return $this->hasMany(CreditNotesDetailsBills::class,'id_credit_notes_clients_b');
    }
}
