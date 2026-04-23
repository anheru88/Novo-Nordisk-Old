<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNoteClientBill extends Model
{
    //
    protected $primaryKey = 'id_credit_notes_clients_b';

    protected $fillable = [
        'client_sap_code',
        'concept',
        'bill',
        'id_credit_notes',
    ];


    
    public function creditNotes()
    {
        return $this->belongsTo(CreditNote::class, 'id_credit_notes', 'id_credit_notes');
    }

    public function creditNotesDetailsB()
    {
        return $this->hasMany(CreditNoteDetailBill::class, 'id_credit_notes_clients_b', 'id_credit_notes_clients_b');
    }
}
