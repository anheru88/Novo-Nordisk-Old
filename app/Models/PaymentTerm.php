<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'percent',
        'code',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'payterm_id', 'id');
    }

    public function quotationsDetails()
    {
        return $this->hasMany(QuotationDetail::class, 'payterm_id', 'id');
    }
}
