<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegotiationDetail extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'negotiation_id',
        'client_id',
        'product_id',
        'concept_id',
        'aclaracion',
        'suj_volumen',
        'quantity',
        'units',
        'discount',
        'discount_type',
        'discount_acum',
        'observations',
        'prod_auth_level_id',
        'authlevel',
        'is_valid',
        'quotation_id',
        'scale_id',
        'id_scale_lvl',
        'visible',
        'warning',
    ];

    // Scope

    public function scopeCheckInformacionConcepto($query, $concepto) {}

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class, 'negotiation_id', 'id');
    }

    public function prodAuthLevel()
    {
        return $this->belongsTo(ProductAuthLevel::class, 'prod_auth_level_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function negotiationsErrors()
    {
        return $this->hasMany(NegotiationError::class, 'negotiation_det_id', 'id');
    }
}
