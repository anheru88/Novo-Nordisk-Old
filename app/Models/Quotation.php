<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quotation extends Model
{
    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'city_id',
        'is_authorized',
        'authorizer_user_id',
        'channel_id',
        'value',
        'date_ini',
        'date_end',
        'created_by',
        'id_auth_level',
        'pre_aproved',
        'comments',
        'number',
        'consecutive',
        'comments_auth',
        'comments_pre',
        'status_id',
    ];

    public $sortable = [
        'id',
        'consecutive',
        'client_id',
        'channel_id',
        'created_by',
        'date_ini',
        'date_end',
        'status_id',
    ];

    // Cancel overdue quotes by date
    public static function updateQuotationsbyDate()
    {
        $date = Carbon::yesterday();
        $yesterday = $date->toDateTimeString();

        Quotation::where('date_end', '<', $yesterday)
            ->where(function ($query) {
                return $query
                    ->where('is_authorized', '<', 7)
                    ->orWhere('status_id', '<=', 6);
            })->update(['is_authorized' => 9]);

        $products = DB::table('quotations')
            ->leftjoin('quotation_details', 'quotation_details.quotation_id', '=', 'quotations.id')
            ->select('quotations.id')
            ->where('quotations.is_authorized', '=', 7)
            ->whereIn('quotation_details.is_valid', [0, 6])
            ->groupBy('quotations.id')
            ->get();

        foreach ($products as $quota) {
            QuotationDetail::where('quotation_id', $quota->id)->update(['is_valid' => 9]);
        }
    }

    // Cancel quotes when all their products overdue
    public static function updateQuotationsbyProducts()
    {
        $overdueQuotas = DB::table('quotations')
            ->leftjoin('quotation_details', 'quotation_details.quotation_id', '=', 'quotations.id')
            ->select('quotations.id')
            ->where('quotations.is_authorized', '<=', 6)
            ->where('quotation_details.is_valid', '=', 8)
            ->groupBy('quotations.id')
            ->get();

        foreach ($overdueQuotas as $key => $quota) {
            $queryQuota = QuotationDetail::where('quotation_id', $quota->id)->where('quotation_details.is_valid', 6)->count();
            if ($queryQuota == 0) {
                Quotation::where('id', $quota->id)->update(['is_authorized' => 8, 'status_id' => 8]);
            }
        }
    }

    public static function updateQuotationsbyApprovals($id, $status)
    {

        $today = Carbon::today();

        $quotation = Quotation::where('id', $id)->with('quotadetails', 'cliente')->first();
        $idClient = $quotation->cliente->id;
        $dateIni = $quotation->date_ini;
        $dateEnd = $quotation->date_end;
        if ($status == 6) {
            foreach ($quotation->quotadetails as $key => $product) {
                if ($dateIni == $today) {
                    QuotationDetail::where('client_id', $idClient)
                        ->whereHas('quotation', function ($query) use ($dateIni, $status, $today) {
                            return $query
                                ->where('status_id', $status)
                                ->whereDate('date_ini', '<=', $today)
                                ->whereDate('date_end', '>=', $today)
                                ->whereDate('date_ini', '<=', $dateIni)
                                ->whereDate('date_end', '>=', $dateIni);
                            /*->orWhereDate('date_ini', $dateIni)
                        ->where('date_ini',  $dateEnd)
                        ->orWhere('date_end',  $dateIni)
                        ->orWhere('date_ini',  $dateEnd)
                        ->orWhere('date_end',  $dateEnd)
                        ->orWhereBetween('date_ini', [$dateIni, $dateEnd])
                        ->orWhereBetween('date_end', [$dateIni, $dateEnd]);*/
                        })
                        ->where('quotation_id', '!=', $id)
                        ->whereIn('is_valid', [1, 6])
                        ->where('product_id', $product->id)
                        ->update(['is_valid' => 8]);
                } else {
                    QuotationDetail::where('client_id', $idClient)
                        ->whereHas('quotation', function ($query) use ($dateIni, $dateEnd, $status) {
                            return $query
                                ->where('status_id', $status)
                                ->where('date_ini', $dateIni)
                                ->orWhere('date_end', $dateIni)
                                ->orWhere('date_ini', $dateEnd)
                                ->orWhere('date_end', $dateEnd);
                            /*->orWhereBetween('date_ini', [$dateIni, $dateEnd])
                        ->orWhereBetween('date_end', [$dateIni, $dateEnd]);*/
                        })
                        ->where('quotation_id', '!=', $id)
                        ->whereIn('is_valid', [1, 6])
                        ->where('product_id', $product->id)
                        ->update(['is_valid' => 8]);
                }

            }
        }
    }

    public function channel()
    {
        return $this->belongsTo(DistChannel::class, 'channel_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(Location::class, 'city_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function quotationxstatus()
    {
        return $this->hasMany(QuotationStatusChange::class, 'quotation_id', 'id');
    }

    public function quotationApprovers()
    {
        return $this->hasMany(QuotationApprover::class, 'quotation_id', 'id');
    }

    public function quotationxcomments()
    {
        return $this->hasMany(QuotationComment::class, 'quotation_id', 'id');
    }

    public function quotationsDetails()
    {
        return $this->hasMany(QuotationDetail::class, 'quotation_id', 'id');
    }

    public function quotationxdocs()
    {
        return $this->hasMany(QuotationDoc::class, 'quotation_id', 'id');
    }
}
