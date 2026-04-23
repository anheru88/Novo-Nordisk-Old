<?php

namespace App\Http\Controllers;

use App\Events\OrderNotificationsEvent;
use App\Location;
use App\Negotiation;
use App\NegotiationComments;
use App\NegotiationDetails;
use App\Notifications;
use App\Quotation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class PreAutorizationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idUser =  auth()->user()->id;
        $isPre = auth()->user()->hasPermissionTo('preautorizacion');

        if ($isPre) {
            $quotations = Quotation::whereHas('status', function($query){
                $query->where('status_id','=',1);
            })->orderBy('id_quotation', 'DESC')
            ->with('cliente', 'channel', 'status')
            ->get();


            $negotiations = Negotiation::orWhereHas('status', function($query){
                $query->where('status_id','=',1);
            })->orderBy('id_negotiation', 'DESC')
            ->with('cliente', 'channel', 'status')
            ->get();

            return view('admin.preautorizations.index', compact('quotations', 'negotiations'));
        } else {
            abort(403, 'Acción no autorizada.');
        }

    }

    public function quotations($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('preautorizacion');
        if ($isEditor) {
            $quotation = Quotation::where('id_quotation', $id)->with('quotadetails', 'status', 'city','docs','usercomments')->first();
            $autorizing = User::where('is_authorizer', 1)->orderBy('name','asc')->pluck('name', 'id');
            return view('admin.preautorizations.quotations', compact('quotation', 'autorizing'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    // Negotiations
    public function negotiations($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('preautorizacion');
        if ($isEditor) {
            $negotiation = Negotiation::where('id_negotiation', $id)->with('negodetails', 'cliente', 'channel', 'city','documents','usercomments')->first();
            $autorizing = User::where('is_authorizer', 1)->orderBy('name','asc')->pluck('name', 'id');
            $negodetails = NegotiationDetails::where('id_negotiation', $id)
                ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_negotiations_details.id_product') // se incluyo el join de la tabla para ordenar por el nombre del producto
                ->orderBy('nvn_products.prod_name')
                ->select('nvn_negotiations_details.*')
                ->with('errors','quotation')
                ->get();
            return view('admin.preautorizations.negotiations', compact('negotiation', 'autorizing', 'negodetails'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function approved()
    {
        $level = Auth::user()->authlevel;
        $quotations = Quotation::where('id_authorizer_user', $level)->where('is_authorized', 4)->orderBy('id_quotation', 'DESC')->with('cliente', 'channel')->get();
        return view('admin.autorizations.aproved', compact('quotations'));
    }

    public function rejected()
    {
        $level = Auth::user()->authlevel;
        $quotations = Quotation::where('id_authorizer_user', $level)->where('is_authorized', 5)->orderBy('id_quotation', 'DESC')->with('cliente', 'channel')->get();
        return view('admin.autorizations.rejected', compact('quotations'));
    }
}
