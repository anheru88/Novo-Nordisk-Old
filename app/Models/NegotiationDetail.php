<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegotiationDetail extends Model
{
    protected $primaryKey = 'id_negotiation_det';



    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_negotiation',
        'id_client',
        'id_product',
        'id_concept',
        'aclaracion',
        'suj_volumen',
        'quantity',
        'units',
        'discount',
        'discount_type',
        'discount_acum',
        'observations',
        'id_prod_auth_level',
        'authlevel',
        'is_valid',
        'id_quotation',
        'id_scale',
        'id_scale_lvl',
        'visible',
        'warning',
    ];


    //Scope

    public function scopeCheckInformacionConcepto($query, $concepto){

    }


    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class, 'id_negotiation', 'id_negotiation');
    }

    public function prodAuthLevel()
    {
        return $this->belongsTo(ProductAuthLevel::class, 'id_prod_auth_level', 'id_level');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function negotiationsErrors()
    {
        return $this->hasMany(NegotiationError::class, 'id_negotiation_det', 'id_negotiation_det');
    }
}
