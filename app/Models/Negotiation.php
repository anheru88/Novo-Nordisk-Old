<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Negotiation extends Model
{
    protected $primaryKey = 'id_negotiation';

    protected $fillable = [
        'id_client',
        'id_city',
        'is_authorized',
        'id_authorizer_user',
        'id_channel',
        'id_auth_level',
        'negotiation_date_ini',
        'negotiation_date_end',
        'pre_approved',
        'comments',
        'created_by',
        'negotiation_consecutive',
        'negotiation_number',
        'status_id',
        'pdf_content',
    ];

    public $sortable = [
        'id_negotiation',
        'negotiation_consecutive',
        'id_client',
        'negotiation_date_ini',
        'negotiation_date_end',
        'status_id',
    ];

    public function approversNeg($id_negotiation){
        $approversSign = NegotiationApprover::where('negotiation_id',$id_negotiation)->get();
        return $approversSign;
    }


    // Verifica la vigencia de las negociaciones por fecha

    public static function updateNegotiationsbyDate(){
        $date = Carbon::yesterday();
        $yesterday = $date->toDateTimeString();
        //dd($yesterday);
        $negotiationsActive = Negotiation::where('negotiation_date_end', '<=', $yesterday)
        ->where(function ($query) {
            return $query
            ->where('is_authorized', '<', 6)
            ->orWhere('status_id','<=',6);
        })
        ->update(['is_authorized' => 7, 'status_id' => 9]);

        $nego = DB::table('negotiations')
        ->leftJoin('negotiation_details', 'negotiation_details.id_negotiation', '=', 'negotiations.id_negotiation')
        ->select('negotiations.id_negotiation')
        ->where(function ($query) {
            $query->where('negotiations.is_authorized', '=', 7)
                ->orWhere('negotiations.status_id', '=', 8);
        })
        ->where('negotiation_details.is_valid', '<', 7)
        ->groupBy('negotiations.id_negotiation')
        ->get();
        foreach ($nego as $key => $negot) {
            NegotiationDetail::where('id_negotiation', $negot->id_negotiation)->update(['is_valid' => 0]);
        }
    }

    // Cambia el estado de los productos en la negociación actual y en las que pueda encontrarlo
    public static function updateNegotiationsbyAprovations($id, $status, $idClient, $dateIni, $dateEnd, $product){

        $today = Carbon::today();

        if($status == 6){
            NegotiationDetail::where('id_client', $idClient)
            ->whereHas('negotiation', function ($query) use ($dateIni, $dateEnd, $status, $today) {
                return $query
                    ->where('status_id', $status)
                    ->whereDate('negotiation_date_ini', $today)
                    ->whereDate('negotiation_date_ini', $dateIni);
            })
            ->where('discount_type', $product->discount_type)
            ->where('id_negotiation','!=',$id)
            ->where('discount_acum', $product->discount_acum)
            ->whereIn('is_valid', [3,6])
            ->where('id_product', $product->id_product)
            ->with('negotiation')
            ->update(['is_valid' => 1]);
        }
    }

     // Cancel negotiations when all their products overdue
     public static function updateSingleNegotiationbyProducts(){
        $overdueNego = DB::table('negotiations')
        ->leftjoin('negotiation_details', 'negotiation_details.id_negotiation', '=', 'negotiations.id_negotiation')
        ->select('negotiations.id_negotiation')
        ->where('negotiation_details.is_valid', '=', 0)
        ->groupBy('negotiations.id_negotiation')
        ->get();

        foreach ($overdueNego as $nego) {
            Negotiation::where('id_negotiation', $nego->id_negotiation)->update(['is_authorized' => 7]);
        }
    }


    public function channel()
    {
        return $this->belongsTo(DistChannel::class, 'id_channel', 'id_channel');
    }

    public function city()
    {
        return $this->belongsTo(Location::class, 'id_city', 'id_locations');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function negotiationsDetails()
    {
        return $this->hasMany(NegotiationDetail::class, 'id_negotiation', 'id_negotiation');
    }

    public function negotiationxstatus()
    {
        return $this->hasMany(NegotiationStatusChange::class, 'negotiation_id', 'id_negotiation');
    }

    public function negotiationxcomments()
    {
        return $this->hasMany(NegotiationComment::class, 'id_negotiation', 'id_negotiation');
    }

    public function negotiationxdocs()
    {
        return $this->hasMany(NegotiationDoc::class, 'id_negotiation', 'id_negotiation');
    }

    public function negotiationxapprovers()
    {
        return $this->hasMany(NegotiationApprover::class, 'negotiation_id', 'id_negotiation');
    }
}
