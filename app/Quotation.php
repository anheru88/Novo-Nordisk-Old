<?php

namespace App;

use App\Events\OrderNotificationsEvent;
use App\Traits\GenericTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Quotation extends Model
{
    use Sortable, GenericTrait;

    protected $table = 'nvn_quotations';

    protected $primaryKey = 'id_quotation';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'id_client', 'id_city', 'is_authorized', 'id_authorizer_user', 'id_channel', 'quota_value', 'quota_date_ini', 'quota_date_end', 'created_by', 'status_id'
    ];

    public $sortable = [
        'id_quotation',
        'quota_consecutive',
        'id_client',
        'id_channel',
        'created_by',
        'quota_date_ini',
        'quota_date_end',
        'status_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function quotadetails()
    {
        return $this->hasMany(QuotationDetails::class,'id_quotation');
    }

    public function channel(){
        return $this->belongsTo(Channel_Types::class, 'id_channel');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_authorizer_user');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function city()
    {
        return $this->belongsTo(Location::class,'id_city');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function usercomments()
    {
        return $this->hasMany(QuotationxComments::class,'id_quotation');
    }

    public function docs()
    {
        return $this->hasMany(QuotationxDocs::class,'id_quotation');
    }

    public function approving(){
        return $this->hasMany(QuotationApprovers::class,'quotation_id');
    }

    // Cancel overdue quotes by date
    public static function updateQuotationsbyDate(){
        $date = Carbon::yesterday();
        $yesterday = $date->toDateTimeString();

        Quotation::where('quota_date_end', '<', $yesterday)
        ->where(function ($query) {
            return $query
            ->where('is_authorized', '<', 7)
            ->orWhere('status_id','<=',6);
        })->update(['is_authorized' => 9]);

        $products = DB::table('nvn_quotations')
            ->leftjoin('nvn_quotations_details', 'nvn_quotations_details.id_quotation', '=', 'nvn_quotations.id_quotation')
            ->select('nvn_quotations.id_quotation')
            ->where('nvn_quotations.is_authorized', '=', 7)
            ->whereIn('nvn_quotations_details.is_valid', [0,6])
            ->groupBy('nvn_quotations.id_quotation')
            ->get();

        foreach ($products as $quota) {
            QuotationDetails::where('id_quotation', $quota->id_quotation)->update(['is_valid' => 9]);
        }
    }

    // Cancel quotes when all their products overdue
    public static function updateQuotationsbyProducts(){
        $overdueQuotas = DB::table('nvn_quotations')
        ->leftjoin('nvn_quotations_details', 'nvn_quotations_details.id_quotation', '=', 'nvn_quotations.id_quotation')
        ->select('nvn_quotations.id_quotation')
        ->where('nvn_quotations.is_authorized', '<=', 6)
        ->where('nvn_quotations_details.is_valid', '=', 8)
        ->groupBy('nvn_quotations.id_quotation')
        ->get();

        foreach ($overdueQuotas as $key => $quota) {
            $queryQuota = QuotationDetails::where('id_quotation', $quota->id_quotation)->where('nvn_quotations_details.is_valid', 6)->count();
            if($queryQuota == 0){
                Quotation::where('id_quotation', $quota->id_quotation)->update(['is_authorized' => 8, 'status_id' => 8]);
            }
        }
    }

    public static function updateQuotationsbyApprovals($id, $status){

        $today = Carbon::today();

        $quotation  = Quotation::where('id_quotation',$id)->with('quotadetails','cliente')->first();
        $idClient   = $quotation->cliente->id_client;
        $dateIni    = $quotation->quota_date_ini;
        $dateEnd    = $quotation->quota_date_end;
        if($status == 6){
            foreach ($quotation->quotadetails as $key => $product) {
                if($dateIni == $today){
                    QuotationDetails::where('id_client', $idClient)
                    ->whereHas('quotation', function ($query) use ($dateIni, $dateEnd, $status, $today) {
                    return $query
                        ->where('status_id', $status)
                        ->whereDate('quota_date_ini', '<=' ,$today)
                        ->whereDate('quota_date_end', '>=', $today)
                        ->whereDate('quota_date_ini', '<=', $dateIni)
                        ->whereDate('quota_date_end', '>=', $dateIni);
                        /*->orWhereDate('quota_date_ini', $dateIni)
                        ->where('quota_date_ini',  $dateEnd)
                        ->orWhere('quota_date_end',  $dateIni)
                        ->orWhere('quota_date_ini',  $dateEnd)
                        ->orWhere('quota_date_end',  $dateEnd)
                        ->orWhereBetween('quota_date_ini', [$dateIni, $dateEnd])
                        ->orWhereBetween('quota_date_end', [$dateIni, $dateEnd]);*/
                    })
                    ->where('id_quotation','!=',$id)
                    ->whereIn('is_valid', [1,6])
                    ->where('id_product', $product->id_product)
                    ->update(['is_valid' => 8]);
                }else{
                    QuotationDetails::where('id_client', $idClient)
                    ->whereHas('quotation', function ($query) use ($dateIni, $dateEnd, $status, $today) {
                    return $query
                        ->where('status_id', $status)
                        ->where('quota_date_ini', $dateIni)
                        ->orWhere('quota_date_end',  $dateIni)
                        ->orWhere('quota_date_ini',  $dateEnd)
                        ->orWhere('quota_date_end',  $dateEnd);
                        /*->orWhereBetween('quota_date_ini', [$dateIni, $dateEnd])
                        ->orWhereBetween('quota_date_end', [$dateIni, $dateEnd]);*/
                    })
                    ->where('id_quotation','!=',$id)
                    ->whereIn('is_valid', [1,6])
                    ->where('id_product', $product->id_product)
                    ->update(['is_valid' => 8]);
                }

            }
        }
    }

}
