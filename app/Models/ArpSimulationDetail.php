<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArpSimulationDetail extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'arp_simulation_id',
        'brand_id',
        'product_id',
        'client_id',
        'cal_year_month',
        'vol_type',
        'forecast_vol',
        'sales_pack_vol',
        'volume',
        'asp_cop',
        'amount_mcop',
        'amount_dkk',
        'currency',
        'net_sales',
        'version',
        'versen',
        'year',
        'quarter',
        'month',
        'cam_id',
        'cam_status',
        'consumption_data',
        'bu',
        'cluster',
        'region',
    ];

    //Relations
    public function scopeSumDetails($query, $idProduct)
    {
        $query = ArpSimulationDetail::groupBy('client_id','product_id')
        ->selectRaw('sum(volume) as volume, sum(amount_mcop) as valuecop, client_id, product_id ')
        ->where('product_id',$idProduct);

        return $query->get();
    }

    public function arpSimulation()
    {
        return $this->belongsTo(ArpSimulation::class, 'arp_simulation_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id_brand');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id_client');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}
