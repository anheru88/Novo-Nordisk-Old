<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNoteDetail extends Model
{
    protected $fillable = [
        'credit_notes_clients_id',
        'prod_sap_code',
        'real_qty',
        'nc_value',
        'nc_individual',
        'tab_xls',
        'concept',
    ];

    public function creditNotesClients()
    {
        return $this->belongsTo(CreditNoteClient::class, 'credit_notes_clients_id', 'id');
    }
}
