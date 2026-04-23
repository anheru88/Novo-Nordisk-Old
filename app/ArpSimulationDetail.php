<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArpSimulationDetail extends Model
{
    protected $table = 'nvn_arp_simulations_details';
    protected $primaryKey = 'id';
    protected $fillable = ['arp_simulation_id','brand_id','product_id','client_id','cal_year_month','vol_type','forecast_vol','sales_pack_vol','volume','asp_cop','amount_mcop',
    'amount_dkk','currency','net_sales','version','versen','year','quarter','month','cam_id','cam_status','consumption_data','bu','group','cluster','region'];

    //Relations
    public function details(){
        return $this->hasMany(ArpSimulationDetail::class);
    }

    public function scopeSumDetails($query, $idProduct)
    {
        $query = ArpSimulationDetail::groupBy('client_id','product_id')
        ->selectRaw('sum(volume) as volume, sum(amount_mcop) as valuecop, client_id, product_id ')
        ->where('product_id',$idProduct);

        return $query->get();
    }
}
