<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_payment_terms';

    protected $primaryKey = 'id_payterms';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'prod_name', 'payterm_name', 'payterm_percent'
    ];
}
