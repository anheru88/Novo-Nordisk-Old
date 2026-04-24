<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Negotiation extends Model
{
    protected $fillable = [
        'client_id',
        'city_id',
        'is_authorized',
        'authorizer_user_id',
        'channel_id',
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
        'id',
        'negotiation_consecutive',
        'client_id',
        'negotiation_date_ini',
        'negotiation_date_end',
        'status_id',
    ];

    public function approversNeg($id_negotiation)
    {
        $approversSign = NegotiationApprover::where('negotiation_id', $id_negotiation)->get();

        return $approversSign;
    }

    // Verifica la vigencia de las negociaciones por fecha

    public static function updateNegotiationsbyDate()
    {
        $date = Carbon::yesterday();
        $yesterday = $date->toDateTimeString();
        // dd($yesterday);
        $negotiationsActive = Negotiation::where('negotiation_date_end', '<=', $yesterday)
            ->where(function ($query) {
                return $query
                    ->where('is_authorized', '<', 6)
                    ->orWhere('status_id', '<=', 6);
            })
            ->update(['is_authorized' => 7, 'status_id' => 9]);

        $nego = DB::table('negotiations')
            ->leftJoin('negotiation_details', 'negotiation_details.negotiation_id', '=', 'negotiations.id')
            ->select('negotiations.id')
            ->where(function ($query) {
                $query->where('negotiations.is_authorized', '=', 7)
                    ->orWhere('negotiations.status_id', '=', 8);
            })
            ->where('negotiation_details.is_valid', '<', 7)
            ->groupBy('negotiations.id')
            ->get();
        foreach ($nego as $key => $negot) {
            NegotiationDetail::where('negotiation_id', $negot->id)->update(['is_valid' => 0]);
        }
    }

    // Cambia el estado de los productos en la negociación actual y en las que pueda encontrarlo
    public static function updateNegotiationsbyAprovations($id, $status, $idClient, $dateIni, $dateEnd, $product)
    {

        $today = Carbon::today();

        if ($status == 6) {
            NegotiationDetail::where('client_id', $idClient)
                ->whereHas('negotiation', function ($query) use ($dateIni, $status, $today) {
                    return $query
                        ->where('status_id', $status)
                        ->whereDate('negotiation_date_ini', $today)
                        ->whereDate('negotiation_date_ini', $dateIni);
                })
                ->where('discount_type', $product->discount_type)
                ->where('negotiation_id', '!=', $id)
                ->where('discount_acum', $product->discount_acum)
                ->whereIn('is_valid', [3, 6])
                ->where('product_id', $product->id)
                ->with('negotiation')
                ->update(['is_valid' => 1]);
        }
    }

    // Cancel negotiations when all their products overdue
    public static function updateSingleNegotiationbyProducts()
    {
        $overdueNego = DB::table('negotiations')
            ->leftjoin('negotiation_details', 'negotiation_details.negotiation_id', '=', 'negotiations.id')
            ->select('negotiations.id')
            ->where('negotiation_details.is_valid', '=', 0)
            ->groupBy('negotiations.id')
            ->get();

        foreach ($overdueNego as $nego) {
            Negotiation::where('id', $nego->id)->update(['is_authorized' => 7]);
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

    public function negotiationsDetails()
    {
        return $this->hasMany(NegotiationDetail::class, 'negotiation_id', 'id');
    }

    public function negotiationxstatus()
    {
        return $this->hasMany(NegotiationStatusChange::class, 'negotiation_id', 'id');
    }

    public function negotiationxcomments()
    {
        return $this->hasMany(NegotiationComment::class, 'negotiation_id', 'id');
    }

    public function negotiationxdocs()
    {
        return $this->hasMany(NegotiationDoc::class, 'negotiation_id', 'id');
    }

    public function negotiationxapprovers()
    {
        return $this->hasMany(NegotiationApprover::class, 'negotiation_id', 'id');
    }
}
