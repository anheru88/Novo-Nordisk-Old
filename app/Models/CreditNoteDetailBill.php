<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNoteDetailBill extends Model
{
    protected $primaryKey = 'id_credit_notes_details_b';
    protected $fillable = [
        'id_credit_notes_clients_b',
        'prod_sap_code',
        'real_qty',
        'nc_value',
        'nc_individual',
        'tab_xls',
        'concept',
    ];


    
    public function creditNotesClientsB()
    {
        return $this->belongsTo(CreditNoteClientBill::class, 'id_credit_notes_clients_b', 'id_credit_notes_clients_b');
    }
}
