<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditNotesClients extends Model
{
    protected $table = 'nvn_credit_notes_clients';

    protected $primaryKey = 'id_credit_notes_clients';

    protected $fillable = ['id_credit_notes_clients','client_sap_code','concept','bill','id_credit_notes'];


    public function details(){
        return $this->hasMany(CreditNotesDetails::class,'id_credit_notes_clients');
    }
}
