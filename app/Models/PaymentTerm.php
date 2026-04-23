<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    protected $primaryKey = 'id_payterms';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'payterm_name',
        'payterm_percent',
        'payterm_code',
    ];

    
    public function clients()
    {
        return $this->hasMany(Client::class, 'id_payterm', 'id_payterms');
    }

    public function quotationsDetails()
    {
        return $this->hasMany(QuotationDetail::class, 'id_payterm', 'id_payterms');
    }
}
