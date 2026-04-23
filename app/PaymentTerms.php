<?php

namespace App;

use Auth0\SDK\API\Management\Clients;
use Illuminate\Database\Eloquent\Model;

class PaymentTerms extends Model
{
    protected $table = 'nvn_payment_terms';

    protected $primaryKey = 'id_payterms';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['payterm_name','payterm_percent','payment_code'];

    public function clients(){
        return $this->hasMany(Clients::class);
    }
}
