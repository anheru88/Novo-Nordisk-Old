<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NegotiationDetails extends Model
{
    protected $table = 'nvn_negotiations_details';

    protected $primaryKey = 'id_negotiation_det';



    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_negotiation', 'id_client', 'id_product', 'id_concept','discount','discount_type','discount_acum','id_quotation','suj_volumen','warning',
    ];


    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'id_quotation');
    }

    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class, 'id_negotiation');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function concept(){
        return $this->belongsTo(NegotiationConcepts::class,'id_concept');
    }

    public function payterm()
	{
		return $this->belongsTo(PaymentTerms::class, 'id_payterm');
    }

    public function client()
	{
		return $this->belongsTo(Client::class, 'id_client');
	}

    public function errors()
    {
        return $this->hasMany(NegotiationErrors::class,'id_negotiation_det');
    }

    //Scope

    public function scopeCheckInformacionConcepto($query, $concepto){

    }

}
