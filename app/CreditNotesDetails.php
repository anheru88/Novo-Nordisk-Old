<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditNotesDetails extends Model
{
    protected $table = 'nvn_credit_notes_details';
    protected $primaryKey = 'id_credit_notes_details';
    protected $fillable = ['id_credit_notes_details','id_credit_notes_clients','client_sap_code','prod_sap_code', 'real_qty','nc_value','nc_individual','concept','tab_xls'];


    public function crenote(){
        return $this->belongsTo(CreditNotesClients::class, 'id_credit_notes_clients');
    }
}
