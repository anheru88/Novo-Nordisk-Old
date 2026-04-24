<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = false;
    // Tabla a negociar

    protected $table = 'status';

    protected $primaryKey = 'status_id';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
        'symbol',
    ];

    public function ScopeGetName($query, $status_id)
    {
        return $query->where('status_id', $status_id);
    }

    public function quotationxstatus()
    {
        return $this->hasMany(QuotationStatusChange::class, 'status_id', 'status_id');
    }

    public function negotiationxstatus()
    {
        return $this->hasMany(NegotiationStatusChange::class, 'status_id', 'status_id');
    }
}
