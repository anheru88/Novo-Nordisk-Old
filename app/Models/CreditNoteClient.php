<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNoteClient extends Model
{
    protected $fillable = [
        'client_sap_code',
        'concept',
        'bill',
        'credit_notes_id',
    ];

    public function creditNotes()
    {
        return $this->belongsTo(CreditNote::class, 'credit_notes_id', 'id');
    }

    public function creditNotesDetails()
    {
        return $this->hasMany(CreditNoteDetail::class, 'credit_notes_clients_id', 'id');
    }
}
