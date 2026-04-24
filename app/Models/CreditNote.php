<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    //
    protected $fillable = [
        'name',
    ];

    public function creditNotesClients()
    {
        return $this->hasMany(CreditNoteClient::class, 'credit_notes_id', 'id');
    }

    public function creditNotesClientsBills()
    {
        return $this->hasMany(CreditNoteClientBill::class, 'credit_notes_id', 'id');
    }
}
