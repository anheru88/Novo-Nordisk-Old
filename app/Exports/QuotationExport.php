<?php

namespace App\Exports;

use App\QuotationDetails;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class QuotationExport implements FromView
{
    use Exportable;

    public function __construct($oldReq)
    {
        $this->oldReq = $oldReq;
    }
    public function view():View
    {

        $oldReq = $this->oldReq;
        $details = QuotationDetails::orderBy('id_quotation', 'DESC');


        if($oldReq['user'] != ""){
            $details = $details->whereHas('quotation', function ($query) use( $oldReq ) {
                $query->where('created_by', '<=', $oldReq['user']);
            });
        }

        if($oldReq['product'] != ""){
            $details = $details->whereHas('product', function ($query) use( $oldReq ) {
                $query->where('id_product', $oldReq['product']);
            });
        }

        if ($oldReq['desde'] != "") {
            $details = $details->whereHas('quotation', function ($query) use( $oldReq ) {
                $query->where('quota_date_ini', '<=', $oldReq['desde'])
                ->where('quota_date_end', '>=', $oldReq['desde']);
            });
        }

        if ($oldReq['hasta'] != "") {
            $details = $details->whereHas('quotation', function ($query) use( $oldReq ) {
                $query->where('quota_date_end', '<=', $oldReq['hasta']);
            });
        }

        if ($oldReq['hasta'] != "") {
            $details = $details->whereHas('quotation', function ($query) use( $oldReq ) {
                $query->where('id_channel', $oldReq['channel']);
            });
        }

        $details = $details->with('quotation','product','payterm','client')->take(10000)->get();

        return view('admin.reports.reportsexceltemplate', compact('details'));

    }
}
