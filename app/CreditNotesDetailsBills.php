<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditNotesDetailsBills extends Model
{
    protected $table = 'nvn_credit_notes_details_b';
    protected $primaryKey = 'id_credit_notes_details_b';
    protected $fillable = ['id_credit_notes_details_b','id_credit_notes_clients_b','client_sap_code','prod_sap_code', 'real_qty','nc_value','nc_individual','concept','tab_xls'];


    public function crenote(){
        return $this->belongsTo(CreditNotesClientsBills::class, 'id_credit_notes_clients_b');
    }
}
