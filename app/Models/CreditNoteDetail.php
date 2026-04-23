<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNoteDetail extends Model
{
    protected $primaryKey = 'id_credit_notes_details';
    protected $fillable = [
        'id_credit_notes_clients',
        'prod_sap_code',
        'real_qty',
        'nc_value',
        'nc_individual',
        'tab_xls',
        'concept',
    ];


    
    public function creditNotesClients()
    {
        return $this->belongsTo(CreditNoteClient::class, 'id_credit_notes_clients', 'id_credit_notes_clients');
    }
}
