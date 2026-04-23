<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    //
    protected $primaryKey = 'id_credit_notes';

    protected $fillable = [
        'doc_name',
    ];


    
    public function creditNotesClients()
    {
        return $this->hasMany(CreditNoteClient::class, 'id_credit_notes', 'id_credit_notes');
    }

    public function creditNotesClientsBills()
    {
        return $this->hasMany(CreditNoteClientBill::class, 'id_credit_notes', 'id_credit_notes');
    }
}
