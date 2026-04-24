<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNoteDetailBill extends Model
{
    protected $fillable = [
        'credit_notes_clients_b_id',
        'prod_sap_code',
        'real_qty',
        'nc_value',
        'nc_individual',
        'tab_xls',
        'concept',
    ];

    public function creditNotesClientsB()
    {
        return $this->belongsTo(CreditNoteClientBill::class, 'credit_notes_clients_b_id', 'id');
    }
}
