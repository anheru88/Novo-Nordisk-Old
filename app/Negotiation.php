<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Negotiation extends Model
{
    use Sortable;

    protected $table = 'nvn_negotiations';

    protected $primaryKey = 'id_negotiation';

    protected $fillable = [
        'id_negotiation', 'id_client', 'id_city', 'is_authorized', 'id_authorizer_user', 'id_channel', 'negotiation_value',
         'negotiation_date_ini', 'negotiation_date_end', 'created_by', 'pdf_content'
    ];

    public $sortable = [
        'id_negotiation',
        'negotiation_consecutive',
        'id_client',
        'negotiation_date_ini',
        'negotiation_date_end',
        'status_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function negodetails()
    {
        return $this->hasMany(NegotiationDetails::class,'id_negotiation');
    }

    public function channel(){
        return $this->belongsTo(Channel_Types::class, 'id_channel');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_authorizer_user');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function usercomments()
    {
        return $this->hasMany(NegotiationComments::class,'id_negotiation');
    }

    public function city(){
        return $this->belongsTo(Location::class,'id_city');
    }

    public function documents()
    {
        return $this->hasMany(NegotiationDocs::class,'id_negotiation');
    }

    public function approving(){
        return $this->hasMany(NegotiationApprovers::class,'negotiation_id');
    }

    public function approversNeg($id_negotiation){
        $approversSign = NegotiationApprovers::where('negotiation_id',$id_negotiation)->get();
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

        $nego = DB::table('nvn_negotiations')
        ->leftJoin('nvn_negotiations_details', 'nvn_negotiations_details.id_negotiation', '=', 'nvn_negotiations.id_negotiation')
        ->select('nvn_negotiations.id_negotiation')
        ->where(function ($query) {
            $query->where('nvn_negotiations.is_authorized', '=', 7)
                ->orWhere('nvn_negotiations.status_id', '=', 8);
        })
        ->where('nvn_negotiations_details.is_valid', '<', 7)
        ->groupBy('nvn_negotiations.id_negotiation')
        ->get();
        foreach ($nego as $key => $negot) {
            NegotiationDetails::where('id_negotiation', $negot->id_negotiation)->update(['is_valid' => 0]);
        }
    }

    // Cambia el estado de los productos en la negociación actual y en las que pueda encontrarlo
    public static function updateNegotiationsbyAprovations($id, $status, $idClient, $dateIni, $dateEnd, $product){

        $today = Carbon::today();

        if($status == 6){
            NegotiationDetails::where('id_client', $idClient)
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
        $overdueNego = DB::table('nvn_negotiations')
        ->leftjoin('nvn_negotiations_details', 'nvn_negotiations_details.id_negotiation', '=', 'nvn_negotiations.id_negotiation')
        ->select('nvn_negotiations.id_negotiation')
        ->where('nvn_negotiations_details.is_valid', '=', 0)
        ->groupBy('nvn_negotiations.id_negotiation')
        ->get();

        foreach ($overdueNego as $nego) {
            Negotiation::where('id_negotiation', $nego->id_negotiation)->update(['is_authorized' => 7]);
        }
    }

}
