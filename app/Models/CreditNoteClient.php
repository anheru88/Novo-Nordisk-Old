<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNoteClient extends Model
{
    protected $primaryKey = 'id_credit_notes_clients';

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

    public function creditNotesDetails()
    {
        return $this->hasMany(CreditNoteDetail::class, 'id_credit_notes_clients', 'id_credit_notes_clients');
    }
}
