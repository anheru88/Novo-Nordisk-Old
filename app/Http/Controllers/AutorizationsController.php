<?php

namespace App\Http\Controllers;


use App\Events\OrderNotificationsEvent;
use App\Location;
use App\Negotiation;
use App\NegotiationApprovers;
use App\NegotiationComments;
use App\NegotiationDetails;
use App\NegotiationDocs;
use App\NegotiationxStatus;
use App\Notifications;
use App\PaymentTerms;
use App\PricesList;
use App\Product_AuthLevels;
use App\ProductxPrices;
use App\Quotation;
use App\QuotationApprovers;
use App\QuotationxComments;
use App\QuotationxDocs;
use App\QuotationxStatus;
use App\Status;
use App\Traits\GenericTrait;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class AutorizationsController extends Controller
{

    use GenericTrait;

    /** Display a listing of the resource.  */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('autorizaciones.index');
        if ($isEditor) {
            if (Auth::user()->is_authorizer == 1) {
                $auth = Auth::user()->id;
                $level = Auth::user()->authlevel;
                $quotations = $this->getQuotationsIndex($level);
                $negotiations = $this->getNegotiationsIndex($level);
                // $negotiations   = Negotiation::where('id_authorizer_user', $auth)->where('pre_approved', 1)->where('is_authorized', 1)->orWhere('is_authorized', 2)->orderBy('id_negotiation', 'DESC')->with('cliente', 'channel')->get();
                $pricelists     = PricesList::where('id_authorizer_user', $auth)->where('active', 0)->orderBy('id_pricelists', 'DESC')->get();
                return view('admin.autorizations.index', compact('quotations', 'negotiations', 'pricelists'));
            }
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function getQuotationsIndex($level)
    {
        if ($level > 0) {
            $quotations = Quotation::whereHas('approving', function ($query) {
                $query->where('user_id', Auth::user()->id)
                ->whereNull('answer');
            })
                ->whereHas('status', function ($query) {
                    $query->where('status_id', '>=', 3)
                        ->where('status_id', '<=', 5);
                })
                ->with('cliente', 'channel', 'status', 'approving')
                ->get();
            foreach ($quotations as $key => $quotation) {
                $approvers = QuotationApprovers::where('quotation_id', $quotation->id_quotation)
                ->whereHas('approversUser', function ($query) {
                    $query->where('authlevel', '<', Auth::user()->authlevel);
                })
                ->whereNull('answer')
                ->get();
                if (!$approvers->isEmpty()) {
                    unset($quotations[$key]);
                }
            }
        } else {
            $quotations = Quotation::whereHas('approving', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
                ->with('cliente', 'channel', 'status', 'approving')
                ->get();
        }
        return $quotations;
    }

    public function getNegotiationsIndex($level)
    {

        if ($level > 0) {
            $negotiations = Negotiation::whereHas('approving', function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->whereNull('answer');
            })
                ->whereHas('status', function ($query) {
                    $query->where('status_id', '>=', 3)
                        ->where('status_id', '<=', 5);
                })
                ->with('cliente', 'channel', 'status', 'approving')
                ->get();
            foreach ($negotiations as $key => $negotiation) {
                $nego = NegotiationApprovers::where('negotiation_id', $negotiation->id_negotiation)
                    ->whereHas('approversUser', function ($query) {
                        $query->where('authlevel', '<', Auth::user()->authlevel);
                    })
                    ->whereNull('answer')
                    ->get();
                if (!$nego->isEmpty()) {
                    unset($negotiation[$key]);
                }
            }
        } else {
            $negotiations = Negotiation::whereHas('approving', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
                ->with('cliente', 'channel', 'status', 'approving')
                ->get();
        }

        return $negotiations;
    }

    /*** Show the quotation to approve. */
    public function quotations($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('autorizaciones.index');
        if ($isEditor) {
            $quotation = Quotation::where('id_quotation', $id)->with('quotadetails', 'status', 'city', 'docs', 'usercomments', 'approving')->first();
            $quota = QuotationApprovers::where('quotation_id', $id)
                ->whereHas('approversUser', function ($query) {
                    $query->where('authlevel', '<>', Auth::user()->authlevel);
                })
                ->where('user_id', '!=', Auth::user()->id)
                ->whereNull('answer')
                ->get();
            if ($quota->isEmpty()) {
                $answer = 6;
            } else {
                $answer = $quotation->status->status_id + 1;
            }

            $approvers = QuotationApprovers::where('quotation_id', $id)
                ->whereHas('approversUser', function ($query) {
                    $query->where('is_authorizer', 1)
                        ->groupBy('id');
                })->get();

            $autorizing = User::where('is_authorizer', 1)
                ->where('authlevel', '<=', $quotation->id_auth_level)
                ->where('authlevel', '>', Auth()->user()->authlevel)
                ->orderBy('name', 'asc')->pluck('name', 'id');

            $autorizer = Auth()->user()->name;
            $authlvl = Auth()->user()->authlevel;
            return view('admin.autorizations.quotations', compact('quotation', 'autorizing', 'autorizer', 'authlvl', 'answer', 'approvers'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    // Change the status of quotations to autorize
    public function autorizeQuotation(Request $request, $id)
    {
        $users = [];
        $comments = $request->comment;
        if ($request->id_authorizer) {
            if ($request->id_authorizer[0] != null) $users = $request->id_authorizer;
        }
        // Get the status name and ID
        $status = intval($request['respuesta']);
        $statusName = Status::getName($status)->pluck('status_name')->first();

        // Update the quotation status
        $quota = Quotation::where('id_quotation', $id)->with('cliente', 'users')->first();
        $quota->status_id = $status;

        // If update, continue with the relations
        if ($quota->update()) {
            // Upload file if have one
            $hasFile = $request->hasFile('docs');
            if ($hasFile) {
                $files = $request->file('docs');
                $folder = $quota->id_quotation;
                QuotationxDocs::storeFiles($files, $folder);
            }
            // Update the old products in another quotas
            Quotation::updateQuotationsbyApprovals($id,$status);

            // Update the status of the listed products
            $quota->quotadetails()->update(['is_valid' => $status]);


            if($status >= 6){
                $users_notified = Role::with('users')->where('slug', 'admin_venta')->get();
                foreach ($users_notified as $user) {
                    array_push($users, $user->users[0]->id);
                }
            }


            // Update and send notifications
            $this->updateQuotationStatus($quota, $status, $users, $id, $statusName, $comments);

            // Return to view successful
            return redirect()->route('cotizaciones.index')->with('info', 'La cotización se cambio a estado ' . $statusName . ' exitosamente');
        } else {
            return redirect()->route('cotizaciones.index')->with('error', 'Existio un problema al modificar la cotización');
        }
    }

    public function negotiations($id)
    {
        $roles = auth()->user()->roles;
        $rol = $roles[0]->slug;
        $isEditor = auth()->user()->hasPermissionTo('autorizaciones.index');
        if ($isEditor) {
            $negotiation = Negotiation::where('id_negotiation', $id)->with('negodetails', 'status', 'city', 'documents', 'usercomments', 'approving')->first();
            $approvers = NegotiationApprovers::where('negotiation_id', $id)
            ->with('approversUser')
            ->orderBy('id','ASC')
            ->get(['user_id']);

            $approversSign = NegotiationApprovers::where('negotiation_id', $id)
                ->whereHas('approversUser', function ($query) {
                    $query->where('authlevel', '<>', Auth::user()->authlevel);
                })
                ->where('user_id', '!=', Auth::user()->id)
                ->whereNull('answer')
                ->get();

            // despues de verificar si quedan autorizadores asigna el estado de aprobacion si no hay mas autorizadores, o el de un estado mas arriba si aun hay.
            if ($approversSign->isEmpty()) {
                $answer = 6;
            } else {
                $answer = $negotiation->status->status_id + 1;
            }

            $autorizing = User::where('is_authorizer', 1)
                ->where('authlevel', '<=', $negotiation->id_auth_level)
                ->where('authlevel', '>', Auth()->user()->authlevel)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id');

            $negodetails = NegotiationDetails::where('id_negotiation', $id)
                ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_negotiations_details.id_product') // se incluyo el join de la tabla para ordenar por el nombre del producto
                ->orderBy('nvn_products.prod_name')
                ->select('nvn_negotiations_details.*')
                ->get();

            $autorizer = Auth()->user()->name;
            $authlvl = Auth()->user()->authlevel;

            return view('admin.autorizations.negotiations', compact('negotiation', 'negodetails', 'autorizing', 'autorizer', 'authlvl', 'answer', 'approvers'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }
    // Aprobacion o rechazo de negociacion - Autorizadores
    public function autorizeNegotiation(Request $request, $id)
    {
        $autorizing = [];
        if ($request->id_authorizer) {
            if ($request->id_authorizer[0] != null) $autorizing = $request->id_authorizer;
        }
        // get the status name
        $answer = $request['respuesta'];
        $statusName = Status::where('status_id', $answer)->pluck('status_name');
        $nego = Negotiation::where('id_negotiation', $id)->with('cliente', 'negodetails')->first();
        $nego->status_id  = $request['respuesta'];

        if ($nego->update()) {

            NegotiationComments::create([
                'id_negotiation'  => $id,
                'created_by'    => Auth()->user()->id,
                'type_comment'  => $statusName,
                'text_comment'  => $request->comment,
            ]);
            //Change autorizer aprobation
            NegotiationApprovers::where('negotiation_id', $id)
                ->where('user_id', Auth()->user()->id)
                ->update(['answer' => $answer]);
            // if has autorizing add to the DB
            if (sizeof($autorizing) > 0) {
                foreach ($autorizing as $key => $autorizer) {
                    $saveApprovers                      = new NegotiationApprovers();
                    $saveApprovers->negotiation_id      = $id;
                    $saveApprovers->user_id             = $autorizer;
                    $saveApprovers->save();
                }
            }
            // upload file if have one
            $hasFile = $request->hasFile('docs');
            if ($hasFile) {
                $files = $request->file('docs');
                $folder = $nego->id_negotiation;
                NegotiationDocs::storeFiles($files, $folder);
            }
            // Send Notification
            $url = 'negociaciones/' . $id;
            $msg = 'La negociación ' . $nego->negotiation_consecutive . ' se encuentra en estado ' . $statusName;
            array_push($autorizing, strval($nego->creator->id));

            NegotiationxStatus::create([
                'status_id'         => $answer,
                'user_id'           => Auth()->user()->id,
                'negotiation_id'    => $id,
            ]);

            foreach ($nego->negodetails as $product) {
                $prod = Negotiation::updateNegotiationsbyAprovations($id, $answer, $nego->id_client, $nego->negotiation_date_ini, $nego->negotiation_date_end, $product);
            }

            $nego->negodetails()->update(['is_valid' => $answer]);

            return redirect()->route('negociaciones.index')->with('info', 'La negociacion se cambio a estado ' . $statusName . ' exitosamente');
        } else {
            return redirect()->route('autorizaciones.index')->with('error', 'Existio un problema al modificar la cotización');
        }
    }

    public function validateAutorizers(Request $request)
    {
        $quota_lvl  = $request->quota_lvl;
        $users      = $request->autorizers;
        $user = User::where('authlevel', '>=', $quota_lvl)->whereIn('id', $users)->first('id');
        if ($user) {
            return "OK";
        } else {
            return "FALSE";
        }
    }

    public function pricelist($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('autorizaciones.index');;
        if ($isEditor) {
            $pricelist      = PricesList::where('id_pricelists', $id)->first();
            $productos      = ProductxPrices::where('id_pricelists', $id)->orderBy('id_productxprices', 'ASC')->get();
            $autorizador    = User::where('is_authorizer', 1)->where('id', $pricelist->id_authorizer_user)->first();
            $pricel = [];
            $pricelistcomercial     = [];
            $pricelistinstitucional = [];

            foreach ($productos as $key => $producto) {
                $authlevels = Product_AuthLevels::where('id_pricelists', $id)->where('id_product', $producto->id_product)->where('id_dist_channel', 5)->orderBy('id_level_discount', 'ASC')->get();
                array_push($pricelistcomercial, $authlevels);
            }

            $pricel2 = [];
            foreach ($productos as $key => $producto) {
                $authlevels = Product_AuthLevels::where('id_pricelists', $id)->where('id_product', $producto->id_product)->where('id_dist_channel', 6)->orderBy('id_level_discount', 'ASC')->get();
                array_push($pricelistinstitucional, $authlevels);
            }
            return view('admin.autorizations.list', compact('pricelist', 'autorizador', 'productos', 'pricelistcomercial', 'pricelistinstitucional'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function approved()  // Show Approved Quotations
    {
        $level = Auth::user()->authlevel;
        //dd($level);
        $quotations = Quotation::where('id_authorizer_user', $level)->where('is_authorized', 4)->orderBy('id_quotation', 'DESC')->with('cliente', 'channel')->get();
        //dd($clientes);
        return view('admin.autorizations.aproved', compact('quotations'));
    }

    public function rejected() // Show Rejected Quotations
    {
        $level = Auth::user()->authlevel;
        //dd($level);
        $quotations = Quotation::where('id_authorizer_user', $level)->where('is_authorized', 5)->orderBy('id_quotation', 'DESC')->with('cliente', 'channel')->get();
        //dd($clientes);
        return view('admin.autorizations.rejected', compact('quotations'));
    }

    // Aprobacion de lista de precios
    public function listapproved(Request $request)
    {
        // Modifica el valor de vigencia final en la lista inmediatamente anterior y donde se trsnlapen fechas
        //$lastprodversion = ProductxPrices::where('id_product',$product->id_product)->where('prod_valid_date_ini' ,'<' ,$dateIni)->where('prod_valid_date_end' ,'>=' ,$dateEnd)->where('active',1)->first();
        $prodlist = ProductxPrices::where('id_pricelists', $request->id_pricelists)->where('version', '>=', 1)->where('active', 0)->get();
        // dd($prodlist);
        if (sizeof($prodlist) > 0 && $prodlist[0]->version > 1) {
            foreach ($prodlist as $key => $prod) {
                $oldProd = ProductxPrices::where('id_product', $prod->id_product)->where('prod_valid_date_end', '>=', $prod->prod_valid_date_ini)->where('active', 1)->first();
                //dd($oldProd);
                if ($oldProd != "") {
                    $datenew = new DateTime($prod->prod_valid_date_ini);
                    $datenew->format('YY-mm-dd H:i:s');
                    $datenew->modify('-1 day');
                    $oldProd->prod_valid_date_end = $datenew;
                    $oldProd->update();
                }
            }
        }

        $listapproved = PricesList::where('id_pricelists', $request->id_pricelists)->update(['active' => '1']);
        $listprods = ProductxPrices::where('id_pricelists', $request->id_pricelists)->update(['active' => '1']);
        $authlvl = Product_AuthLevels::where('id_pricelists', $request->id_pricelists)->update(['active' => '1']);
        $notiUsers = [];
        $users_notified = User::whereHas(
            'roles',
            function ($q) {
                $q->where('slug', 'admin_venta');
                $q->where('slug', 'adminprecios');
            }
        )->get();
        $list = PricesList::where('id_pricelists', $request->id_pricelists)->first();
        // dd($list);
        foreach ($users_notified as $user) {
            $notification = Notifications::create([
                'destiny_id'    => $user->id,
                'sender_id'     => Auth()->user()->id,
                'type'          => 'Modificación de producto',
                'data'          => 'La lista de precios ' . $list->list_name . ' fue aprobada',
                'url'           => "/prices/" . $list->id_pricelists,
                'readed'        => 0,
            ]);
            dd($user->id);
            array_push($notiUsers, $user->id);
        }
        $url = URL::to("/");
        $not['description']    = 'La lista de precios ' . $list->list_name . ' fue aprobada';
        $not['url']            = $url . "prices/" . $list->id_pricelists;
        $not['userId']         = $notiUsers;
        event(new OrderNotificationsEvent($not));
        return redirect()->route('autorizaciones.index')->with('success', 'Lista aprobada exitosamente');
    }

    public function listrejected(Request $request)
    {
        $listupdate = PricesList::where('id_pricelists', $request->id_pricelists)->update(['active' => '2', 'comments' => $request->comments]);
        $list = PricesList::where('id_pricelists', $request->id_pricelists)->first();
        $listprods = ProductxPrices::where('id_pricelists', $request->id_pricelists)->update(['active' => '2']);
        $authlvl = Product_AuthLevels::where('id_pricelists', $request->id_pricelists)->update(['active' => '2']);
        $notiUsers = [];

        $users_notified = User::whereHas(
            'roles',
            function ($q) {
                $q->where('slug', 'admin_venta');
                $q->where('slug', 'adminprecios');
            }
        )->get();

        foreach ($users_notified as $user) {
            $notification = Notifications::create([
                'destiny_id'    => $user->id,
                'sender_id'     => Auth()->user()->id,
                'type'          => 'Aprobacion de lista de precios',
                'data'          => 'La lista de precios ' . $list->list_name . ' fue rechazada',
                'url'           => "/pices/" . $list->id_pricelists,
                'readed'        => 0,
            ]);
            array_push($notiUsers, $user->id);
        }
        $url = URL::to("/");
        $not['description']    = 'La lista de precios ' . $list->list_name . ' fue rechazada';
        $not['url']            = $url . "prices/";
        $not['userId']         = $notiUsers;
        event(new OrderNotificationsEvent($not));
        return redirect()->route('autorizaciones.index')->with('error', 'Lista rechazada exitosamente');
    }
}
