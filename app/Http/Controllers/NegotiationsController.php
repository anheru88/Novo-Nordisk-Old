<?php

namespace App\Http\Controllers;

use App\Channel_Types;
use App\Client;
use App\ClientxProductScale;
use App\Location;
use App\MeasureUnit;
use App\Negotiation;
use App\NegotiationApprovers;
use App\NegotiationComments;
use App\NegotiationConcepts;
use App\NegotiationDetails;
use App\NegotiationDocs;
use App\NegotiationErrors;
use App\NegotiationxStatus;
use App\Notifications;
use App\Product_AuthLevels;
use App\Product_Line;
use App\ProductxPrices;
use App\QuotationDetails;
use App\Scales;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use PDF;

class NegotiationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Artisan::call('update:negotiation-status-defeated');
        Negotiation::updateNegotiationsbyDate();
        $isEditor = auth()->user()->hasPermissionTo('negociaciones.index');
        $filter = $request->query('filter');
        $quantity = $request->query('quantity');
        if ($isEditor) {
            $estado = 0;
            $roles = auth()->user()->roles;
            $rol = $roles[0]->slug;
            $negotiations = $this->queryWithFilter($rol, $filter, $quantity);
            return view('admin.negotiations.index', compact('negotiations', 'estado', 'rol','quantity','filter'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function queryWithFilter($rol, $filter, $quantity)
    {
        if (!empty($filter)) {
            $date = date('Y-m-d', strtotime($filter));
            $negotiations = Negotiation::sortable(['id_negotiation' => 'DESC'])->with('cliente', 'channel', 'status');
            if ($rol == "cam") {
                $negotiations =  $negotiations->where('created_by', auth()->user()->id);
            }
            $negotiations =  $negotiations->where(function ($query) use ($filter, $date) {
                $query->orWhereHas('channel', function ($query) use ($filter) {
                    $query->where('channel_name', 'ILIKE', '%' . $filter . '%');
                });
                $query->orWhereHas('creator', function ($query) use ($filter) {
                    $query->where('name', 'ILIKE', '%' . $filter . '%');
                });
                $query->orWhereHas('cliente', function ($query) use ($filter) {
                    $query->where('client_name', 'ILIKE', '%' . $filter . '%');
                });
                $query->orWhereDate('negotiation_date_ini', '=', $date)
                    ->orWhereDate('negotiation_date_end', '=', $date)
                    ->orWhere('negotiation_consecutive', 'ILIKE', '%' . $filter . '%');
            });
            $negotiations =  $negotiations->paginate($quantity);
        } else {
            $negotiations = Negotiation::sortable(['id_negotiation' => 'DESC'])->with('cliente', 'channel', 'status');
            if ($rol == "cam") {
                $negotiations =  $negotiations->where('created_by', auth()->user()->id);
            }
            $negotiations = $negotiations->paginate($quantity);
        }
        return $negotiations;
    }

    public function create()
    {
        $isEditor = auth()->user()->hasPermissionTo('negociaciones.create');
        if ($isEditor) {
            $roles = auth()->user()->roles;

            $concepts = NegotiationConcepts::orderBy('id_negotiation_concepts', 'ASC')->pluck('name_concept', 'id_negotiation_concepts');
            $measure_units = MeasureUnit::orderBy('unit_name', 'ASC')->pluck('unit_name', 'id_unit');
            if ($roles[0]->slug == "cam") {
                $clientes = Client::where('id_diab_contact', auth()->user()->id)->orderBy('id_client', 'ASC')->where('active', 1)->pluck('client_name', 'id_client');
            } else {
                $clientes = Client::orderBy('id_client', 'ASC')->where('active', 1)->pluck('client_name', 'id_client');
            }

            $canales = Channel_Types::all();
            return view('admin.negotiations.create', compact('clientes', 'canales', 'concepts', 'measure_units'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function store(Request $request)
    {
        //return $request;
        $thisyear = now()->year;
        $lastNegotiation = Negotiation::whereYear('created_at', $thisyear)->orderBy('id_negotiation', 'desc')->first();
        $idClient = $request->idClient;
        $time = strtotime($request->fechaini);
        $dateIni = date('Y-m-d H:i:s', $time);
        $time = strtotime($request->fechafin);
        $dateEnd = date('Y-m-d H:i:s', $time);
        $authLevel = $request->authLevel;
        $products = $request->products;
        //dd($authLevel);
        if ($lastNegotiation) {
            $negotiation_num = intval($lastNegotiation->negotiation_number) + 1;
        } else {
            $negotiation_num = 1;
        }
        if (sizeof($products) > 0) {
            $negotiation_consecutive = 'N-' . $negotiation_num . '-' . $thisyear;
            $client = Client::where('id_client', $idClient)->with('city', 'channel')->first();
            $isEditor = auth()->user()->hasPermissionTo('negociaciones.create');

            if ($isEditor) {
                $negotiation =  new Negotiation();
                $negotiation->id_client                 = $idClient;
                $negotiation->id_city                   = $client->id_city;
                $negotiation->id_authorizer_user        = $request->id_authorizer_user;
                $negotiation->id_channel                = $client->id_client_channel;
                $negotiation->id_auth_level             = $authLevel;
                $negotiation->negotiation_date_ini      = $dateIni;
                $negotiation->negotiation_date_end      = $dateEnd;
                $negotiation->negotiation_number        = $negotiation_num;
                $negotiation->negotiation_consecutive   = $negotiation_consecutive;
                $negotiation->is_authorized             = 1;
                $negotiation->status_id                 = 1;
                $negotiation->created_by                = Auth::user()->id;
                //return $request;

                if ($negotiation->save()) {
                    if (count($products) > 0) {
                        if ($authLevel <= 1 || $authLevel = "") {
                            $valid = 1;
                        } else {
                            $valid = 3;
                        }
                        if (sizeof($products) > 0) {
                            $msg = $this->storeNegotiationProducts($products, $negotiation, $valid);
                            return $msg;
                        } else {
                            return "ERROR";
                        }
                    }
                } else {
                    return "ERROR";
                }
                //return redirect()->route('negociaciones.index')->with('info', 'Negociación creada satisfactoriamente');
            } else {
                abort(403, 'Acción no autorizada.');
            }
        } else {
            return "ERROR";
        }
    }

    public function storeNegotiationProducts($products, $negotiation, $valid)
    {
        $status = 1;
        foreach ($products as $prod) {
            if ($prod['warning'] <= 1) {
                $details = new NegotiationDetails();
                $details->negotiation()->associate($negotiation);
                $details->id_client             = $negotiation->id_client;
                $details->id_product            = $prod['idProduct'];
                $details->id_concept            = intval($prod['idConcept']);
                $details->aclaracion            = $prod['aclaracion'];
                $details->suj_volumen           = strtoupper($prod['volumen']);
                $details->quantity              = $prod['cantidad'];
                $details->units                 = $prod['idUnidades'];
                $details->discount              = intval($prod['descuento']);
                $details->discount_type         = $prod['tipoDescuento'];
                $details->discount_acum         = $prod['descAcumulado'];
                $details->observations          = $prod['observaciones'];
                $details->authlevel             = $prod['productLevel'];
                $details->id_quotation          = $prod['idQuotation'];
                $details->id_scale              = $prod['idScale'];
                $details->id_scale_lvl          = $prod['idScaleLvl'];
                $details->visible               = $prod['visible'];
                $details->warning               = $prod['warning'];
                $details->is_valid              = $valid;
                // Agregar los errores en la nueva tabla
                if ($details->save()) {
                    if (sizeof($prod['errorNego']) > 0) {
                        foreach ($prod['errorNego'] as $error) {
                            NegotiationErrors::create([
                                'id_negotiation_det'    => $details->id_negotiation_det,
                                'negotiation_error'     => $error
                            ]);
                        }
                    }
                } else {
                    return $prod;
                }
            }
        }


        $idNeg = $negotiation->id_negotiation;
        $msg = "La negociación ". $idNeg . "se encuentra en estado pendiente";
        $this->storeNegotiationStatus($negotiation, $msg, $status);

        return $idNeg;
    }

    public function storeNegotiationFiles(Request $request)
    {
        if ($request->comment) {
            // dd($request);
            NegotiationComments::create([
                'id_negotiation'    => $request->idNegotiation,
                'created_by'        => Auth()->user()->id,
                'type_comment'      => 'Creación',
                'text_comment'      => $request->comment,
            ]);
        }

        $hasFile = $request->hasFile('docs');
        $files   = $request->file('docs');
        $folder  = $request->idNegotiation;
        $path    = public_path() . '/uploads/negotiations/' . $folder;
        File::makeDirectory($path, $mode = 0777, true, true);
        if ($hasFile) {
            $path = public_path() . '/uploads/negotiations/' . $folder;
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();
                $fileReg  = new NegotiationDocs();
                $fileReg->id_negotiation  = $folder;
                $fileReg->file_folder     = '/uploads/negotiations/' . $folder;
                $fileReg->doc_name        = $fileName;
                if ($fileReg->save()) {
                    $file->move($path, $fileName);
                }
            }
        }

        return redirect()->route('negociaciones.index')->with('info', 'Negociación creada satisfactoriamente');
    }

    public function storeNegotiationStatus($negotiation, $msg, $status)
    {
        $users_notified = Role::with('users')->where('slug', 'admin_venta')->get();
        //$users_notified = User::where('nickname', 'CMNM')->get();
        $notiUsers = [];
        $url = URL::to("/");
        foreach ($users_notified as $user) {
            // Add the first preautorizer
            $addAutorizers = NegotiationApprovers::create([
                'answer'        => 0,
                'negotiation_id'  => $negotiation->id_negotiation,
                'user_id'       => $user->users[0]->id
            ]);
            // Save the Negotiation Status to get a follow-up
            $addStatus = NegotiationxStatus::create([
                'status_id'     => $status,
                'user_id'       => 1,
                'negotiation_id'  => $negotiation->id_negotiation,
            ]);
            // Save and send the notification to the first autorizer
            $addNotification = Notifications::create([
                'destiny_id'    => $user->users[0]->id,
                'sender_id'     => Auth()->user()->id,
                'type'          => 'Aprobación de Cotización',
                'data'          => $msg . $negotiation->negotiation_consecutive,
                'url'           => $url . "/preautorizarnegociacion/" . $negotiation->id_negotiation,
                'readed'        => 0,
            ]);
            array_push($notiUsers, $user->id);
        }

        $not['description']    = 'Se espera la aprobación de la cotización ' . $negotiation->negotiation_consecutive;
        $not['url']            = $url . 'preautorizarnegociacion/' . $negotiation->id_negotiation;
        $not['userId']         = $notiUsers;
        //event(new OrderNotificationsEvent($not)); // Send notificacion to email.
    }

    public function show($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('negociaciones.show');
        if ($isEditor) {
            $comments    = NegotiationComments::where('id_negotiation', $id)->get();
            $negotiation = Negotiation::where('id_negotiation', $id)->with('negodetails', 'cliente', 'channel', 'usercomments', 'users')->first();
            $negodetails = NegotiationDetails::where('id_negotiation', $id)
                ->orderBy('id_concept', 'ASC')
                ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_negotiations_details.id_product') // se incluyo el join de la tabla para ordenar por el nombre del producto
                ->orderBy('nvn_products.prod_name')
                ->select('nvn_negotiations_details.*')
                ->with('errors')
                ->get();
            // dd($negodetails);
            $autorizador = User::where('is_authorizer', 1)->where('id', $negotiation->id_authorizer_user)->first();
            if (!empty($autorizador)) {
                $autorizador = $autorizador->name;
            } else {
                $autorizador = "";
            }
            $location = Location::find($negotiation->cliente->id_city);
            return view('admin.negotiations.show', compact('negotiation', 'autorizador', 'location', 'negodetails', 'comments'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function edit($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('negociaciones.edit');
        if ($isEditor) {
            $concepts = NegotiationConcepts::orderBy('id_negotiation_concepts', 'ASC')->pluck('name_concept', 'id_negotiation_concepts');
            $measure_units = MeasureUnit::orderBy('unit_name', 'ASC')->pluck('unit_name', 'id_unit');
            $negotiation = Negotiation::where('id_negotiation', $id)->with('negodetails', 'cliente', 'channel', 'usercomments')->first();
            //dd($negotiation);
            $autorizador = User::where('is_authorizer', 1)->where('id', $negotiation->id_authorizer_user)->first();
            if (!empty($autorizador)) {
                $autorizador = $autorizador->name;
            } else {
                $autorizador = "";
            }
            $location = Location::find($negotiation->cliente->id_city);
            return view('admin.negotiations.edit', compact('negotiation', 'autorizador', 'location', 'concepts', 'measure_units'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }


    //fix edit date
    public function editdate($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('negociaciones.edit');
        if ($isEditor) {
            $negotiationdate = Negotiation::find($id);
            // dd($negotiationdate);
            return view('admin.negotiations.editdate', compact('negotiationdate'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function update(Request $request)
    {
        $level = $request->authLevel;
        $isEditor = auth()->user()->hasPermissionTo('negociaciones.edit');
        if ($isEditor) {
            $products = $request->products;
            if ($request->authLevel <= 1 || $request->authLevel = "") {
                $valid = 1;
            } else {
                $valid = 2;
            }
            $negotiation = Negotiation::where('id_negotiation', $request->idNegotiation)->update(['id_auth_level' => $level, 'status_id' => 1]);
            $removeProducts = NegotiationDetails::where('id_negotiation', $request->idNegotiation)->delete();
            $negotiation_q = Negotiation::where('id_negotiation', $request->idNegotiation)->first();
            $msg = $this->storeNegotiationProducts($products, $negotiation_q, $valid);
            return $request->idNegotiation; // regresa el id de la negociacion

        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function updatedate(Request $request)
    {
        // dd($request->idNegoti);
        $isEditor = auth()->user()->hasPermissionTo('negociaciones.edit');
        if ($isEditor) {
            $id = $request->idNegoti;
            $negotiation = Negotiation::find($id);
            $negotiation->cliente->client_sap_code  = $request->client_sap_code;
            $negotiation->negotiation_date_ini      = $request->negotiation_date_ini;
            $negotiation->negotiation_date_end      = $request->negotiation_date_end;
            $negotiation->status_id                 = '1';
            $negotiation->is_authorized             = '1';
            // dd($negotiation);
            if ($negotiation->update()) {
                NegotiationDetails::where('id_negotiation', $id)->update(['is_valid' => 1]);
                $msg = "La negociación ". $id . "se encuentra en estado pendiente";
                $this->storeNegotiationStatus($negotiation, $msg, 1);

                return redirect()->route('negociaciones.index')->with('info', 'Fecha actualizada exitosamente');
            } else {
                return redirect()->route('negociaciones.index')->with('error', 'No se pudo actualizar la fecha, verifique los datos proporcionados');
            }
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }


    // AXIOS FUNCTIONS VUE JS
    // Obtiene los productos de la cotizaciones actuales del cliente
    public function getProductsClientQuota(Request $request)
    {
        $id_client = $request->idClient;
        $time = strtotime($request->fechaini);
        $dateIni = date('Y-m-d H:i:s', $time);
        $time = strtotime($request->fechafin);
        $dateEnd = date('Y-m-d H:i:s', $time);

        $client =  Client::where('id_client', $id_client)->with('city', 'channel', 'payterm', 'type')->first();

        $productsQuota = QuotationDetails::where('id_client', $id_client)
            ->where(function($query){
                $query->where('is_valid', 1)
                ->orWhere('is_valid', 6);
            })
            ->whereHas('quotation', function ($query) use ($dateIni, $dateEnd) {
                return $query->where('quota_date_ini', '<=', $dateIni)->where('quota_date_end', '>=', $dateIni); // filtro de fecha inicial y cierre de negociacion
            })
            ->with('payterm', 'quotation')
            ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_quotations_details.id_product') // se incluyo el join de la tabla para ordenar por el nombre del producto
            ->orderBy('nvn_products.prod_name')
            ->select('nvn_quotations_details.*')
            ->get();

        if (sizeof($productsQuota) > 0) {
            foreach ($productsQuota as $key => $product) {
                $products[$key]["client_code"]          = $client->client_sap_code;
                $products[$key]["client_city"]          = $client->city->loc_name;
                $products[$key]["client_idchannel"]     = $client->id_client_channel;
                $products[$key]["client_channel"]       = $client->channel->channel_name;
                $products[$key]["client_payterm"]       = $client->payterm->payterm_name;
                $products[$key]["client_type"]          = $client->type->type_name;
                $products[$key]["id_product"]           = $product->id_product;
                $products[$key]["id_quotation"]         = $product->id_quotation;
                $products[$key]["consecutive"]          = getQuotaConsecutive($product->id_quotation);
                $products[$key]["productname"]          = $product->product->prod_name;
                $products[$key]["quantity"]             = $product->quantity;
                $products[$key]["vComercial"]           = $product->prod_cost;
                $products[$key]["uMinima"]              = $product->price_uminima;
                $products[$key]["vTotal"]               = $product->totalValue;
                $products[$key]["dtoPrecio"]            = $product->pay_discount;
                $products[$key]["fPago"]                = $product->payterm->payterm_name;
                $products[$key]["date_ini"]             = date('d-m-Y', strtotime($product->quotation->quota_date_ini));
                $products[$key]["date_end"]             = date('d-m-Y', strtotime($product->quotation->quota_date_end));

                $scaleid = getProductxClientScales($product->id_product, $id_client);
                if ($scaleid != null) {
                    $scaleName = Scales::where('id_scale', $scaleid[0])->first();
                    $products[$key]["scale"]            = $scaleName->scale_number;
                    $products[$key]["scaleid"]          = $scaleName->id_scale;
                } else {
                    $products[$key]["scale"]            = "No asignada";
                }
            }

            return $products;
        } else {
            return "No data";
        }
    }

    // Obtiene los productos de la negociación actual del cliente
    public function getProductsClientNego(Request $request)
    {
        $negoArray  = [];
        $id_negotiation = $request->idNegotiation;
        $productsNego = NegotiationDetails::where('id_negotiation', $id_negotiation)
            ->where('is_valid', 6)
            ->with('product', 'quotation', 'concept', 'errors')
            ->get();

        if (sizeof($productsNego) > 0) {
            foreach ($productsNego as $key => $product) {
                $msg = [];
                foreach ($product->errors as $error) {
                    array_push($msg, $error->negotiation_error);
                }
                $con = $product->concept;
                if($con){
                    $con = $product->concept->name_concept;
                }else{
                    $con = "ESCALA";
                }
                $productpercent["idProductDiv"]     =   $product->id_product . "-" . ($key + 1);
                $productpercent["idProduct"]        =   $product->id_product;
                $productpercent["idConcept"]        =   $product->id_concept;
                $productpercent["producto"]         =   $product->product->prod_name;
                $productpercent["desc"]             =   $product->discount;
                $productpercent["desc_acum"]        =   $product->discount_acum;
                $productpercent["concepto"]         =   strtoupper($product->discount_type);
                $productpercent["conceptotext"]     =   $con;
                $productpercent["aclaracion"]       =   $product->aclaracion;
                $productpercent["volumen"]          =   $product->suj_volumen;
                $productpercent["cantidad"]         =   $product->quantity;
                $productpercent["unidadesId"]       =   $product->units;
                $productpercent["unidades"]         =   "";
                $productpercent["observaciones"]    =   $product->observations;
                $productpercent["idQuotation"]      =   $product->id_quotation;
                $productpercent["idScale"]          =   $product->id_scale;
                $productpercent["idScaleLvl"]       =   $product->id_scale_level;
                $productpercent["authlevel"]        =   $product->authlevel;
                $productpercent["visible"]          =   $product->visible;
                $productpercent["msg"]              =   $msg;
                $productpercent["finded"]           =   "";
                $productpercent["warning"]          =   $product->warning;
                array_push($negoArray, $productpercent);
            }
            return $negoArray;
        } else {
            return "No data";
        }
    }

    public function getProductsClientDesc(Request $request)
    {
        $idClient       = $request->idClient;
        $idProduct      = $request->idProduct;
        $idQuotation    = $request->idQuotation;

        $client = Client::where('id_client', $idClient)->with('channel')->first();
        $product = QuotationDetails::where('id_client', $idClient)
            ->where('id_product', $idProduct)
            ->where('id_quotation', $idQuotation)
            ->where(function($query){
                $query->where('is_valid', 1)
                ->orWhere('is_valid', 6);
            })
            ->whereHas('quotation', function ($query) {
                $q = $query->where('is_authorized', 6)
                ->orWhere('status_id', '=', 6);
                return $q;
            })
            ->with('product')
            ->with('payterm')
            ->first();

        $queryArray = [];
        $desc = $product->pay_discount;
        $queryArray['clientChannel']    = $client->id_client_channel;
        $queryArray['id_quotation']     = $product->id_quotation;
        $queryArray['pay_discount']     = $desc;
        $queryArray['quotedPrice']      = $product->totalValue;

        return $queryArray;
    }

    // obtiene los descuentos previos de negociaciones anteriores vigentes
    public function checkProductDiscounts($idClient, $idProduct, $idConcept, $clientChannel, $dateIni, $payTermDiscount, $scaleDiscount, $quotedPrice)
    {
        $priceListVersion = ProductxPrices::where('id_product', $idProduct)
            ->where('prod_valid_date_ini', '<=', $dateIni)
            ->where('prod_valid_date_end', '>=', $dateIni)
            ->where('active', 1)
            ->first();

        $listVersion = $priceListVersion->version;
        //return $priceListVersion;

        $queryLevel = Product_AuthLevels::orderBy('id_level_discount', 'ASC')
            ->where('id_dist_channel', $clientChannel)
            ->where('id_product',  $idProduct)
            ->where('version', $listVersion)
            ->where('active', 1)
            ->get(); // Obtengo los niveles y precios de lista segun la fecha de inicio de la negociacion

        //return $queryLevel;
        $desc_lvl1 = $queryLevel[0]->discount_price;
        $desc_lvl2 = $queryLevel[1]->discount_price;
        $desc_lvl3 = $queryLevel[2]->discount_price;
        $desc_lvl4 = $queryLevel[3]->discount_price; // Porcentaje de descuento del nivel 4

        $channel = Channel_Types::where('id_channel', $clientChannel)->first();
        $channelName = strtolower($channel->channel_name);

        if ($channelName == "institucional") {
            $priceList = $priceListVersion->v_institutional_price;
        } else {
            $priceList = $priceListVersion->v_commercial_price;
        }

        $idConcept = strtoupper($idConcept);

        //return $idConcept;

        $discountMaxProduct = NegotiationDetails::orderBy('discount', 'DESC')
            ->where('id_product', $idProduct)
            ->where('id_client', $idClient)
            ->where('id_concept', $idConcept)
            ->whereIn('is_valid', [1, 6])
            ->whereHas('negotiation', function ($query) {
                return $query->where('is_authorized', '=', 4); // Se ajusto que consultara si la negociacion estaba vigente
            })
            ->first(); // Obtiene el descuento mas alto del producto relacionado con el cliente de la tabla nvn_negotiation_details

        //return $discountMaxProduct;

        if ($discountMaxProduct) {
            $discountAccumulated = $discountMaxProduct->discount_acum;
            $idScaleLvl = $discountMaxProduct->id_scale_lvl;
        } else {
            $discountAccumulated = 0;
            $idScaleLvl = 0;
        }

        $percentDiscountNego = $discountAccumulated + $scaleDiscount + $payTermDiscount;

        if ($percentDiscountNego < 0) {
            $percentDiscountNego = $percentDiscountNego * -1;
        }

        $valueDiscount  = $quotedPrice - ($quotedPrice * $percentDiscountNego / 100); //  valor de descuento al aplicarlo al precio cotizado
        $acumPercent    = number_format((($priceList - $valueDiscount) / $priceList) * 100, 2, ".", ","); // porcentaje acumulado de negociacion

        if ($valueDiscount < $desc_lvl4 && $idScaleLvl <= 0) {
            $result['authlevel'] = "";
            $result['msg'] = "Ha superado el maximo de descuento permitido, este no sera agregado a la negociación";
            $result['acumPercent'] = $acumPercent;
            $result['warning'] = 2;
            $result['visible'] = "NO";
        } else {
            if ($valueDiscount >= $priceList) {
                $result['authlevel'] = 2;
                $result['msg']  = "";
                $result['warning'] = "";
            } else if ($valueDiscount < $priceList && $valueDiscount >= $desc_lvl2) {
                $result['authlevel'] = 2;
                $result['msg'] = "";
                $result['warning'] = "";
            } else if ($valueDiscount < $desc_lvl2 && $valueDiscount >= $desc_lvl3) {
                $result['authlevel'] = 2;
                $result['msg'] = "";
                $result['warning'] = "";
            } else if ($valueDiscount < $desc_lvl3 && $valueDiscount >= $desc_lvl4) {
                $result['authlevel'] = 3;
                $result['msg'] = "";
                $result['warning'] = "";
            } else if ($valueDiscount == $desc_lvl4) {
                $result['authlevel'] = 4;
                $result['msg'] = "";
                $result['warning'] = "";
            } else if ($valueDiscount < $desc_lvl4) {
                $result['authlevel'] = 2;
                $result['msg'] = "Ha superado el maximo de descuento permitido, este no sera agregado a la negociación";
                $result['warning'] = 2;
            }
            $result['acumPercent'] = $acumPercent;
        }
        return $result;
    }

    public function negociacionAsistida(Request $request) // Negociacion asistida unciamente x escala.
    {
        $negoArray          = [];
        $time               = strtotime($request->fechaini);
        $dateIni            = date('Y-m-d H:i:s', $time);
        $time               = strtotime($request->fechafin);
        $dateEnd            = date('Y-m-d H:i:s', $time);
        $quotaProductsList  = $request->quotaProductsList;
        $idClient           = $request->idClient;
        $clientChannel      = $request->clientChannel;
        $quotationIds       = $request->quotationIds;
        $idConcept          = 0;
        $finded             = "";


        for ($i = 0; $i < sizeof($quotaProductsList); $i++) {
            // Valida si el producto tiene escala asignada para el cliente.
            $getScales = ClientxProductScale::where('id_client', $idClient)
            ->where('id_product', $quotaProductsList[$i])
            ->first();

            if ($getScales) {
                $idProduct = $getScales->id_product;
                $idClientScale  = $getScales->id_client;
                $idScale        = $getScales->id_scale;
                // Obtiene los datos del producto desde la cotización
                $getProduct = QuotationDetails::where('id_client', $idClientScale)
                    ->where('id_product', $idProduct)
                    ->where(function($query){
                        $query->where('is_valid', 1)
                        ->orWhere('is_valid', 6);
                    })
                    ->where('id_quotation', $quotationIds[$i])
                    ->with('product', 'payterm', 'productAuthlvl')
                    ->first();

                $payTermDiscount    = $getProduct->payterm->payterm_percent; // descuento del producto.
                $quotedPrice        = $getProduct->totalValue / $getProduct->quantity; // valor cotizado en pesos.

                // Obtiene los niveles de escalas del producto.
                $getProductLvls = Scales::where('id_scale', $idScale)->with('scalelvl')->get();

                foreach ($getProductLvls as $key => $prodlvl) {
                    $productpercent = [];
                    foreach ($prodlvl->scalelvl as $key => $level) {
                        $errorNego      = [];
                        $scaleDiscount  = $level->scale_discount;

                        // Verifica si los productos se encuentran en una negociacion anterior, si los encuentra los dejara como inhabilitados
                        $checkprevNegotiation = NegotiationDetails::where('id_client', $idClient)
                        ->where('discount_type', '!=', 'convenio')
                        ->whereHas('negotiation', function ($query) use ($dateIni, $dateEnd) {
                            return $query
                                ->where('status_id','<=', 6)
                                ->where('negotiation_date_ini',  $dateEnd)
                                ->orWhere('negotiation_date_end',  $dateIni)
                                ->orWhere('negotiation_date_ini',  $dateEnd)
                                ->orWhere('negotiation_date_end',  $dateEnd)
                                ->orWhereBetween('negotiation_date_ini', [$dateIni, $dateEnd])
                                ->orWhereBetween('negotiation_date_end', [$dateIni, $dateEnd]);
                        })
                        ->where('id_quotation', $quotationIds[$i])
                        ->where('discount', intval($scaleDiscount))
                        ->where('id_scale', $idScale)
                        ->where('is_valid', 1)
                        ->where('id_product', $idProduct)
                        ->with('negotiation')
                        ->latest()
                        ->first();

                        if($checkprevNegotiation && $idConcept == 0){
                            $visible    = 'NO';
                            $finded     = 'Esta escala ya fue asignada en la negociación ' . $checkprevNegotiation->negotiation->negotiation_consecutive;
                            $warning    = 2;
                        }else if ($checkprevNegotiation) {
                            $visible    = 'SI';
                            $finded     = 'El producto se anulará en la negociación ' . $checkprevNegotiation->negotiation->negotiation_consecutive;
                            $warning    = 1;
                        } else {
                            $visible    = 'SI';
                            $finded     = '';
                            $warning    = 0;
                        }
                        // Verifica los descuentos
                        $acumPercent = $this->checkProductDiscounts($idClient, $idProduct, $idConcept, $clientChannel, $dateIni, $payTermDiscount, $scaleDiscount, $quotedPrice);
                        if ($acumPercent['warning'] != "") {
                            $warning = $acumPercent['warning'];
                            $visible = $acumPercent['visible'];
                        }

                        if ($finded != "") {
                            array_push($errorNego, $finded);
                        }

                        if ($acumPercent['msg'] != "") {
                            array_push($errorNego, $acumPercent['msg']);
                        }

                        // Retorna el producto con los descuentos aplicados
                        if ($acumPercent['acumPercent'] > 0) {
                            $productpercent["idProductDiv"]     =   $idProduct . "-" . ($key + 1);
                            $productpercent["idProduct"]        =   $idProduct;
                            $productpercent["idConcept"]        =   0;
                            $productpercent["producto"]         =   $getProduct->product->prod_name;
                            $productpercent["desc"]             =   $level->scale_discount;
                            $productpercent["desc_acum"]        =   $acumPercent['acumPercent'];
                            $productpercent["concepto"]         =   "escala";
                            $productpercent["aclaracion"]       =   $prodlvl->scale_number . " - Nivel " . ($key + 1);
                            $productpercent["volumen"]          =   "SI";
                            $productpercent["cantidad"]         =   $level->scale_min;
                            $productpercent["unidadesId"]       =   $getProduct->product->measureUnit->id_unit;
                            $productpercent["unidades"]         =   $getProduct->product->measureUnit->unit_name;
                            $productpercent["observaciones"]    =   $level->scale_min . " - " . $level->scale_max;
                            $productpercent["idQuotation"]      =   $getProduct->id_quotation;
                            $productpercent["idScale"]          =   $prodlvl->id_scale;
                            $productpercent["idScaleLvl"]       =   $level->id_scale_level;
                            $productpercent["authlevel"]        =   $acumPercent['authlevel'];
                            $productpercent["visible"]          =   $visible;
                            $productpercent["msg"]              =   $errorNego;
                            $productpercent["finded"]           =   $finded;
                            $productpercent["warning"]          =   $warning;
                            array_push($negoArray, $productpercent);
                        }
                    }
                }
            }
        }

        if (sizeof($negoArray) > 0) {
            return $negoArray;
        } else {
            return "No data";
        }
    }

    public function negociacionAsistidaxConcepto(Request $request) // Negociacion asistida x cualquier concepto diferente a escala.
    {
        $negoArray  = [];
        $time = strtotime($request->fechaini);
        $dateIni = date('Y-m-d H:i:s', $time);
        $time = strtotime($request->fechafin);
        $dateEnd = date('Y-m-d H:i:s', $time);
        $productList        = $request->quotaProductsList; // lista de productos
        $quotationIds       = $request->quotationIds; // ids de cotizaciones de cada producto
        $idClientChannel    = $request->idClientChannel; // Canal del cliente
        $observations       = $request->obsConcept;
        $idConcept          = intval($request->idConcept);
        $idQuotation        = $request->idQuotation;
        $nota               = $request->nota;
        $concept            = $request->concept;
        $finded             = "";

        foreach ($productList as $key => $idProduct) {

            $errorNego          = [];
            $idClient           = $request->client; // id del cliente
            $idConcept          = $request->idConcept; // id del concepto
            $discountNeg        = $request->discount; // descuento agregado
            $discountType       = $request->discount_val; // tipo de descuento seleccionado (Independiente o Agrupado)
            $listDescAcumulated = $request->listDescAcumulated; // lista actual de precios acumulados
            $idQuotation        = $quotationIds[$key];

            $quotedPriceVal = QuotationDetails::where('id_quotation', $idQuotation)
                ->where('id_product', $idProduct)
                ->with('product', 'payterm', 'productAuthlvl')
                ->first();

            $productName = $quotedPriceVal->product->prod_name;
            $quotedPrice = $quotedPriceVal->totalValue;

            try{
                $priceListVersion = ProductxPrices::where('id_product', $idProduct)
                    ->where('prod_valid_date_ini', '<=', $dateIni)
                    ->where('prod_valid_date_end', '>=', $dateEnd)
                    ->where('active', 1)
                    ->first();
                $listVersion = $priceListVersion->version;
            }catch (\Exception $e){
                $queryArray['idProduct']        = $idProduct;
                $queryArray['idQuotation']      = $idQuotation;
                $queryArray['idConcept']        = $idConcept;
                $queryArray['producto']         = $productName;
                $queryArray['msg']              = ["No cuenta con lista de precios para este rango de fechas"];
                $queryArray['error']            = ["No cuenta con lista de precios para este rango de fechas"];
                array_push($negoArray, $queryArray);
                continue;
            }



            $channel = Channel_Types::where('id_channel', $idClientChannel)->first();
            $channelName = strtolower($channel->channel_name);

            if ($channelName == "institucional") {
                $priceList = $priceListVersion->v_institutional_price;
            } else {
                $priceList = $priceListVersion->v_commercial_price;
            }

            /*****************************************************/
            // ESCALA MAS ALTA DEL PRODUCTO SELECCIONADO

            $getScales = ClientxProductScale::where('id_client', $idClient)->where('id_product', $idProduct)->first();
            if ($getScales) {
                $idScale            = $getScales->id_scale;
                $getProductLvls     = Scales::where('id_scale', $idScale)->with('scalelvl')->first();
                $scalesPercent      = $getProductLvls->scalelvl;
            }

            /***********************************************************/
            //OBTIENE LOS PORCENTAJES PREVIOS SI EXISTEN EN NEGOCIACIONES O EN LA LISTA DE PRODUCTOS
            $finantialDiscount  = $this->getfinancialDiscount($idClient, $idProduct, $listDescAcumulated); // obtiene el descuento financiero

            if ($discountType == "convenio") {
                $escalaMaxDiscount      = $this->getMaxPercentListEscalas($idProduct, $listDescAcumulated, $idQuotation);
                $dataBaseDiscount       = $this->getPercentDatabaseConvenio($idClient, $idProduct, $idConcept, $discountType, $idQuotation); // obtiene el porcentaje de negociaciones previas en la base de datos
                $listProductDiscount    = $this->getMaxPercentListConvenio($idProduct,  $idConcept, $listDescAcumulated, $idQuotation);

                // Calcula cual es el mayor de los porcentajes con concepto igual
                $maxPercentConcept[0] = $dataBaseDiscount['maxConcept'];
                $maxPercentConcept[1] = $listProductDiscount['maxConcept'];
                $maxPercentConcept[2] = $discountNeg;
                rsort($maxPercentConcept);

                $maxConcept = $maxPercentConcept[0]; // Base de datos + lista valor maximo de concepto
                $maxNoConcept   = $dataBaseDiscount['maxNoConcept'] + $listProductDiscount['maxNoConcept'];
                $discountAcummulated = $escalaMaxDiscount + ($maxConcept - $dataBaseDiscount['maxConcept']) + $maxNoConcept + $finantialDiscount;
            } else {
                $escalaMaxDiscount      = $this->getMaxPercentListEscalas($idProduct, $listDescAcumulated, $idQuotation);
                $dataBaseMaxDiscount    = $this->getMaxPercentDatabaseNoConvenio($idClient, $idProduct, $idConcept, $listDescAcumulated, $idQuotation);
                $listMaxDiscount        = $this->getMaxPercentListNoConvenio($idProduct, $listDescAcumulated, $idConcept, $idQuotation);
                $discountAcummulated = $escalaMaxDiscount + $dataBaseMaxDiscount + $listMaxDiscount + $discountNeg + $finantialDiscount;
            }

            // CALCULO DE DESCUENTO ACUMULADO
            // Verifica si el descuento de la lista actual es mayor a la de la escala del producto, si es mayor la deja como acumulada
            $valueDiscount  = $quotedPrice - ($quotedPrice * $discountAcummulated / 100); //  valor de descuento al aplicarlo al precio cotizado
            $acumPercent    = number_format((($priceList - $valueDiscount) / $priceList) * 100, 2, ".", ","); // porcentaje acumulado de negociacion

            /***********************************************************/
            // CALCULO DE NIVELES DE AUTORIZACION PARA LA NEGOCIACION

            $queryLevel = Product_AuthLevels::orderBy('id_level_discount', 'ASC')
                ->where('id_dist_channel', $idClientChannel)
                ->where('id_product',  $idProduct)
                ->where('version', $listVersion)
                ->where('active', 1)
                ->get(); // Obtengo los niveles de autorizacion y precios de lista segun la fecha de inicio de la negociacion

            /***********************************************************/
            // AQUI COMPARA CON EL VALOR EN PESOS EN LA LISTA DE PRECIOS //
            $desc_lvl1 = $queryLevel[0]->discount_price;
            $desc_lvl2 = $queryLevel[1]->discount_price;
            $desc_lvl3 = $queryLevel[2]->discount_price;
            $desc_lvl4 = $queryLevel[3]->discount_price;

            $visible = "SI";
            $warning = 0;

            if ($valueDiscount < $desc_lvl4) {
                $idLevel = "";
                $authlevel = 0;
                $queryArray['discountAcum'] = 0;
                $done = 0;
                $msg = "Ha superado el maximo de descuento permitido, este no sera agregado a la negociación";
                $visible = "NO";
                $warning = 2;
            }

            if ($valueDiscount >= $priceList) {
                $idLevel = "";
                $authlevel = 2;
                $done = 1;
                $msg = "";
            } else if ($valueDiscount < $priceList && $valueDiscount >= $desc_lvl2) {
                $idLevel = $queryLevel[0]->id_level;
                $authlevel = 2;
                $done = 1;
                $msg = "";
            } else if ($valueDiscount < $desc_lvl2 && $valueDiscount >= $desc_lvl3) {
                $idLevel = $queryLevel[1]->id_level;
                $authlevel = 2;
                $done = 1;
                $msg = "";
            } else if ($valueDiscount < $desc_lvl3 && $valueDiscount >= $desc_lvl4) {
                $idLevel = $queryLevel[2]->id_level;
                $authlevel = 3;
                $done = 1;
                $msg = "";
            } else if ($valueDiscount == $desc_lvl4) {
                $idLevel = $queryLevel[3]->id_level;
                $authlevel = 4;
                $done = 1;
                $msg = "";
            } else if ($valueDiscount < $desc_lvl4) {
                $idLevel = "";
                $authlevel = 0;
                $queryArray['discountAcum'] = 0;
                $done = 0;
                $msg = "Ha superado el maximo de descuento permitido, este no sera agregado a la negociación";
                $visible = "NO";
                $warning = 2;
            }


            /******************************************************** */
            // VERIFICACION 3
            // Verifica que el concepto no este repetido en negociaciones pasadas, siempre que no sea convenio.
            if ($idConcept == 1) {
                $checkprevNegotiation = NegotiationDetails::where('id_client', $idClient)
                    ->where('discount_type', '!=', 'convenio')
                    ->whereHas('negotiation', function ($query) use ($dateIni, $dateEnd) {
                        return $query->where('negotiation_date_ini',  $dateIni)
                            ->where('status_id','<=', 6)
                            ->orWhere('negotiation_date_end',  $dateIni)
                            ->orWhere('negotiation_date_ini',  $dateEnd)
                            ->orWhere('negotiation_date_end',  $dateEnd)
                            ->orWhereBetween('negotiation_date_ini', [$dateIni, $dateEnd])
                            ->orWhereBetween('negotiation_date_end', [$dateIni, $dateEnd]);
                    })
                    ->where('id_concept', $idConcept)
                    ->where('id_product', $idProduct)
                    ->where('is_valid', 1)
                    ->with('negotiation')
                    ->get();

                if (!$checkprevNegotiation->isEmpty()) {
                    $visible    = 'SI';
                    $finded     = 'El producto ya fue asignado en la negociación ' . $checkprevNegotiation[0]->negotiation->negotiation_consecutive;
                    $warning    = 2;
                } else {
                    $visible    = 'SI';
                    $finded     = '';
                    $warning    = $warning;
                }
            }


            /* Verifica si el concepto por informacion esta dentro de la lista actual, si es asi lo agrega pero con la alerta de no permitido*/
            if($idConcept == 1){
                foreach ($listDescAcumulated as $prod) {
                    if($idProduct == $prod['idProduct'] && $idConcept == $prod['idConcept']){
                        $visible    = 'NO';
                        $finded     = 'El producto con este concepto ya esta en la lista';
                        $warning    = 1;
                    }
                }
            }
            /******************************************************** */

            if ($finded != "") {
                array_push($errorNego, $finded);
            }

            if ($msg != "") {
                array_push($errorNego, $msg);
            }

            if($observations == ""){
                $observations = "N/A";
            }


            $queryArray['idProduct']        = $idProduct;
            $queryArray['idQuotation']      = $idQuotation;
            $queryArray['idConcept']        = $idConcept;
            $queryArray['producto']         = $productName;
            $queryArray['conceptotext']     = $concept;
            $queryArray['observaciones']    = $observations;
            $queryArray['aclaracion']       = $nota;
            $queryArray['concepto']         = $concept;
            $queryArray['volumen']          = 'NO';
            $queryArray["cantidad"]         = '';
            $queryArray["unidadesId"]       = '';
            $queryArray['idLevel']          = $idLevel;
            $queryArray['discount']         = $discountNeg;
            $queryArray['tDiscount']        = $discountType;
            $queryArray['authLevel']        = $authlevel;
            $queryArray['done']             = $done;
            $queryArray['msg']              = $errorNego;
            $queryArray['error']            = $errorNego;
            $queryArray['desc']             = $discountNeg;
            $queryArray['discountPrice']    = number_format($discountAcummulated, 2, ".", ",");
            $queryArray['discountAcum']     = number_format($acumPercent, 2, ".", ",");
            $queryArray['desc_acum']        = number_format($acumPercent, 2, ".", ",");
            $queryArray['visible']          = $visible;
            $queryArray['finded']           = $finded;
            $queryArray['warning']          = $warning;

            array_push($negoArray, $queryArray);
        }

        if (sizeof($negoArray) > 0) {
            return $negoArray;
        } else {
            return "No data";
        }
    }

    // Funcion para detectar el nivel de descuento de la negociacion
    public function checkProductNegotiationLevel($dateIni, $idProduct, $idClientChannel)
    {

        // PRECIO DE LISTA DEL PRODUCTO SELECIONADO
        $priceListVersion = ProductxPrices::where('id_product', $idProduct)
            ->where('prod_valid_date_ini', '<=', $dateIni)
            ->where('prod_valid_date_end', '>=', $dateIni)
            ->where('active', 1)
            ->first();

        $listVersion = $priceListVersion->version;

        // Obtiene el nivel de autorización del producto
        $queryLevel = Product_AuthLevels::orderBy('id_level_discount', 'ASC')
            ->where('id_dist_channel', $idClientChannel)
            ->where('id_product',  $idProduct)
            ->where('version', $listVersion)
            ->get(); // Obtengo los niveles y precios de lista segun la fecha de inicio de la negociacion

        return $queryLevel;
    }

    public function calcDiscount(Request $request)
    {
        $errorNego =  [];

        $time               = strtotime($request->fechaini);
        $dateIni            = date('Y-m-d H:i:s', $time);
        $time               = strtotime($request->fechaend);
        $dateEnd            = date('Y-m-d H:i:s', $time);
        $warning            = 0;

        $idProduct          = $request->idProduct; // id del producto
        $idQuotation        = $request->idQuotation; // id de la cotizacion del producto seleccionado
        $idClient           = $request->client; // id del cliente
        $idConcept          = $request->idConcept; // id del concepto
        $discountNeg        = $request->discount; // descuento agregado
        $discountType       = $request->discount_val; // tipo de descuento seleccionado (Independiente o Agrupado)
        $quotedPrice        = $request->quotedPrice; // total de descuentos del producto
        $descValue          = $request->pay_discount; // valor de descuento del producto
        $listDescAcumulated = $request->listDescAcumulated; // lista actual de precios acumulados
        $clientChannel      = $request->clientChannel;

        try{
            $priceListVersion = ProductxPrices::where('id_product', $idProduct)
                ->where('prod_valid_date_ini', '<=', $dateIni)
                ->where('prod_valid_date_end', '>=', $dateEnd)
                ->where('active', 1)
                ->first();

            $listVersion = $priceListVersion->version;
        }catch (\Exception $e){
            $queryArray['idProduct']        = $idProduct;
            $queryArray['idQuotation']      = $idQuotation;
            $queryArray['idConcept']        = $idConcept;
            $queryArray['msg']              = "No cuenta con lista de precios para este rango de fechas";
            $queryArray['error']            = ["No cuenta con lista de precios para este rango de fechas"];
            return $queryArray;
        }



        $channel = Channel_Types::where('id_channel', $clientChannel)->first();
        $channelName = strtolower($channel->channel_name);

        if ($channelName == "institucional") {
            $priceList = $priceListVersion->v_institutional_price;
        } else {
            $priceList = $priceListVersion->v_commercial_price;
        }

        /*****************************************************/
        // ESCALA MAS ALTA DEL PRODUCTO SELECCIONADO

        $getScales          = ClientxProductScale::where('id_client', $idClient)->where('id_product', $idProduct)->first();
        if ($getScales) {
            $idScale            = $getScales->id_scale;
            $getProductLvls     = Scales::where('id_scale', $idScale)->with('scalelvl')->first();
            $scalesPercent      = $getProductLvls->scalelvl;
        }

        /***********************************************************/
        //OBTIENE LOS PROCENTAJES PREVIOS SI EXISTEN EN NEGOCIACIONES O EN LA LISTA DE PRODUCTOS
        $finantialDiscount  = $this->getfinancialDiscount($idClient, $idProduct, $listDescAcumulated); // obtiene el descuento financiero


        if ($discountType == "convenio") {

            $escalaMaxDiscount      = $this->getMaxPercentListEscalas($idProduct, $listDescAcumulated, $idQuotation);
            $listProductDiscount    = $this->getMaxPercentListConvenio($idProduct,  $idConcept, $listDescAcumulated, $idQuotation);
            $dataBaseDiscount       = $this->getPercentDatabaseConvenio($idClient, $idProduct, $idConcept, $discountType, $idQuotation); // obtiene el porcentaje de negociaciones previas en la base de datos con y sin convenio
            //return $dataBaseDiscount;
            // Calcula cual es el mayor de los porcentajes con concepto igual
            $maxPercentConcept[0] = $dataBaseDiscount['maxConcept'];
            $maxPercentConcept[1] = $listProductDiscount['maxConcept'];
            $maxPercentConcept[2] = $discountNeg;
            rsort($maxPercentConcept);

            $maxConcept = $maxPercentConcept[0]; // Base de datos + lista valor maximo de concepto
            $maxNoConcept   = $dataBaseDiscount['maxNoConcept'] + $listProductDiscount['maxNoConcept'];
            $discountAcummulated = $escalaMaxDiscount + ($maxConcept - $dataBaseDiscount['maxConcept']) + $maxNoConcept + $finantialDiscount;
        } else {
            $escalaMaxDiscount      = $this->getMaxPercentListEscalas($idProduct, $listDescAcumulated, $idQuotation);
            $dataBaseMaxDiscount    = $this->getMaxPercentDatabaseNoConvenio($idClient, $idProduct, $idConcept, $listDescAcumulated, $idQuotation);
            $listMaxDiscount        = $this->getMaxPercentListNoConvenio($idProduct, $listDescAcumulated, $idConcept, $idQuotation); // regresa la suma de descuentos de tipo independiente

            $discountAcummulated = $escalaMaxDiscount + $dataBaseMaxDiscount + $listMaxDiscount + $discountNeg + $finantialDiscount;
            // return $escalaMaxDiscount .'+'. $dataBaseMaxDiscount  .'+'.  $listMaxDiscount  .'+'.  $discountNeg  .'+'.  $finantialDiscount  .'+'.  $listProductDiscount['maxConcept'];;
        }

        // CALCULO DE DESCUENTO ACUMULADO
        // Verifica si el descuento de la lista actual es mayor a la de la escala del producto, si es mayor la deja como acumulada
        $valueDiscount  = round($quotedPrice - ($quotedPrice * $discountAcummulated / 100),0); //  valor de descuento al aplicarlo al precio cotizado
        $acumPercent    = number_format((($priceList - $valueDiscount) / $priceList) * 100, 2, ".", ","); // porcentaje acumulado de negociacion

        /***********************************************************/
        // CALCULO DE NIVELES DE AUTORIZACION PARA LA NEGOCIACION

        $queryLevel = Product_AuthLevels::orderBy('id_level_discount', 'ASC')
            ->where('id_dist_channel', $clientChannel)
            ->where('id_product',  $idProduct)
            ->where('version', $listVersion)
            ->where('active', 1)
            ->get(); // Obtengo los niveles de autorizacion y precios de lista segun la fecha de inicio de la negociacion

        //return $valueDiscount;
        /***********************************************************/
        // AQUI COMPARA CON EL VALOR EN PESOS EN LA LISTA DE PRECIOS // 30/07/2020
        $desc_lvl1 = $queryLevel[0]->discount_price;
        $desc_lvl2 = $queryLevel[1]->discount_price;
        $desc_lvl3 = $queryLevel[2]->discount_price;
        $desc_lvl4 = $queryLevel[3]->discount_price;

        //return $valueDiscount . " ". $desc_lvl1;


        if ($valueDiscount < $desc_lvl4) {
            $idLevel = "";
            $authlevel = "";
            $queryArray['discountAcum'] = 0;
            $done = 0;
            $msg = "Ha superado el maximo de descuento permitido, este no sera agregado a la negociación";
        }

        if ($valueDiscount >= $priceList) {
            $idLevel = "";
            $authlevel = 2;
            $done = 1;
            $msg = "";
        } else if ($valueDiscount < $priceList && $valueDiscount >= $desc_lvl2) {
            $idLevel = $queryLevel[0]->id_level;
            $authlevel = 2;
            $done = 1;
            $msg = "";
        } else if ($valueDiscount < $desc_lvl2 && $valueDiscount >= $desc_lvl3) {
            $idLevel = $queryLevel[1]->id_level;
            $authlevel = 2;
            $done = 1;
            $msg = "";
        } else if ($valueDiscount < $desc_lvl3 && $valueDiscount >= $desc_lvl4) {
            $idLevel = $queryLevel[2]->id_level;
            $authlevel = 3;
            $done = 1;
            $msg = "";
        } else if ($valueDiscount == $desc_lvl4) {
            $idLevel = $queryLevel[3]->id_level;
            $authlevel = 4;
            $done = 1;
            $msg = "";
        } else if ($valueDiscount < $desc_lvl4) {
            $idLevel = "";
            $authlevel = 2;
            $queryArray['discountAcum'] = 0;
            $done = 0;
            $msg = "Ha superado el maximo de descuento permitido, este no sera agregado a la negociación";
        }

        // Pendiente de verificación con Camilo - y de generar el metodo para esto ya que esta repetido en 2 lugares.

       /* if ($idConcept != 4) {
            $checkprevNegotiation = NegotiationDetails::where('id_client', $idClient)
                ->where('discount_type', '!=', 'convenio')
                ->whereHas('negotiation', function ($query) use ($dateIni, $dateEnd) {
                    return $query->where('negotiation_date_ini',  $dateIni)
                        ->orWhere('negotiation_date_end',  $dateIni)
                        ->orWhere('negotiation_date_ini',  $dateEnd)
                        ->orWhere('negotiation_date_end',  $dateEnd)
                        ->orWhereBetween('negotiation_date_ini', [$dateIni, $dateEnd])
                        ->orWhereBetween('negotiation_date_end', [$dateIni, $dateEnd]);
                })
                ->where('id_product', $idProduct)
                ->where('is_valid','>=',1)
                ->with('negotiation')
                ->get();
            if (!$checkprevNegotiation->isEmpty()) {
                $done = 0;
                $msg  = 'El producto con este concepto ya esta una negociación previa';
            } else {
                $done = 1;
                $msg  = '';
            }
        }
        */

        /* Verifica si el concepto por informacion esta dentro de la lista actual, si es asi lo agrega pero con la alerta de no permitido*/
        if($idConcept == 1){
            foreach ($listDescAcumulated as $prod) {
                if($idProduct == $prod['idProduct'] && $idConcept == $prod['idConcept']){
                    $done = 0;
                    $msg     = 'El producto con este concepto ya esta en la lista';
                }
            }
        }


        if ($msg != "") {
            array_push($errorNego, $msg);
        }

        /******************************************************** */

        $queryArray['idLevel']          = $idLevel;
        $queryArray['idConcept']        = $idConcept;
        $queryArray['discount']         = $discountNeg;
        $queryArray['tDiscount']        = $discountType;
        $queryArray['authLevel']        = $authlevel;
        $queryArray['done']             = $done;
        $queryArray['msg']              = $errorNego;
        $queryArray['discountPrice']    = number_format($discountAcummulated, 2, ".", ",");
        $queryArray['discountAcum']     = number_format($acumPercent, 2, ".", ",");
        $queryArray['warning']          = 0;

        return $queryArray;
    }

    public function getFinancialDiscount($idClient, $idProduct, $listDescAcumulated)
    { // Devuelve el descuento financiero

        $queryClient = Client::where('id_client', $idClient)->with('payterm')->first();
        $payTermDiscount = $queryClient->payterm->payterm_percent;

        return $payTermDiscount;
    }

    public function getPercentDatabaseConvenio($idClient, $idProduct, $idConcept, $discountType, $idQuotation)
    { // Devuelve el descuento de negociaciones anteriores en base de datos x convenio

        $discountConceptConvenio = NegotiationDetails::orderBy('discount', 'DESC') // Trae los registros con el mismo tipo de concepto y el mismo tipo de descuento
            ->where('id_product', $idProduct)
            ->where('id_client', $idClient)
            ->where('id_concept', $idConcept)
            ->where('discount_type', $discountType)
            ->where('is_valid', 1)
            ->whereHas('negotiation', function ($query) {
                return $query->where('status_id', '<=', 6); // Se ajusto que consultara si la negociacion estaba vigente
            })
            ->first();

        if ($discountConceptConvenio) {
            $result['maxConcept'] =  $discountConceptConvenio->discount;
        } else {
            $result['maxConcept'] = 0;
        }

        $discountMaxEscala = NegotiationDetails::orderBy('discount', 'DESC') // Trae el mas alto de la escala
            ->where('id_product', $idProduct)
            ->where('id_client', $idClient)
            ->where('discount_type', "escala")
            ->where('id_quotation', $idQuotation)
            ->where('is_valid', 1)
            ->whereHas('negotiation', function ($query) {
                return $query->where('status_id', '<=', 6) // Se ajusto que consultara si la negociacion estaba vigente
                ->orWhere('is_valid', '<=', 6);
            })
            ->first();

        if ($discountMaxEscala) {
            $maxEscala = $discountMaxEscala->discount;
        } else {
            $maxEscala = 0;
        }


        $concepts = NegotiationConcepts::orderBy('id_negotiation_concepts', 'ASC')->pluck('id_negotiation_concepts');
        $allConcepts = [];
        foreach ($concepts as $key => $concept) {
            $discountConvenioNoConcepto = NegotiationDetails::orderBy('discount', 'DESC')
                ->where('id_product', $idProduct)
                ->where('id_client', $idClient)
                ->where('id_concept', $concept)
                ->where('id_quotation', $idQuotation)
                ->where('discount_type', $discountType)
                ->where('is_valid', 1)
                ->whereHas('negotiation', function ($query) {
                    return $query->where('status_id', '<=', 6)
                    ->orWhere('is_valid', '<=', 6); // Se ajusto que consultara si la negociacion estaba vigente
                })
                ->first();
            if ($discountConvenioNoConcepto) {
                array_push($allConcepts, $discountConvenioNoConcepto['discount']);
            }
        }
        $maxSoloConvenio = array_sum($allConcepts);

        $discountIndependiente = NegotiationDetails::where('id_product', $idProduct) // Trae la suma de los descuentos tipo independiente
            ->where('id_client', $idClient)
            ->where('discount_type', 'independiente')
            ->where('id_quotation', $idQuotation)
            ->where('is_valid', 1)
            ->whereHas('negotiation', function ($query) {
                return $query->where('status_id', '<=', 6) // Se ajusto que consultara si la negociacion estaba vigente
                ->orWhere('is_valid', '<=', 6);
            })
            ->get()
            ->sum('discount');

        if ($discountIndependiente) {
            $descIndependiente = $discountIndependiente;
        } else {
            $descIndependiente = 0;
        }

        $result['maxNoConcept'] = $maxEscala + $descIndependiente + $maxSoloConvenio;

        return $result;
    }

    public function getMaxPercentListConvenio($idProduct, $idConcept, $listDescAcumulated, $idQuotation)
    {
        $result = [];
        // Maximo descuento de lista por conceptos convenio
        $concepts = NegotiationConcepts::orderBy('id_negotiation_concepts', 'ASC')->pluck('id_negotiation_concepts');
        $independiente = [];
        $convenioNoConcept = [];
        $convenioConcept = [];
        //return $concepts;
        foreach ($concepts as $key => $concept) {

            $tempConvenioEqual  = [];
            $tempIndepend       = [];
            $tempConvenio       = [];

            foreach ($listDescAcumulated as $key => $prodlist) {
                if ($prodlist['warning'] == 0) {
                    if ($prodlist['idProduct'] == $idProduct) {
                        if ($prodlist['idConcept'] == $concept && $prodlist['tipoDescuento'] == 'convenio' && $prodlist['idConcept'] == $idConcept && $prodlist['id_quotation'] == $idQuotation) {
                            array_push($tempConvenioEqual, $prodlist['descuento']);
                        }
                        if ($prodlist['idConcept'] == $concept && $prodlist['tipoDescuento'] == 'convenio' && $prodlist['idConcept'] != $idConcept && $prodlist['id_quotation'] == $idQuotation) {
                            array_push($tempConvenio, $prodlist['descuento']);
                        }
                        if ($prodlist['idConcept'] == $concept && $prodlist['tipoDescuento'] == 'independiente' && $prodlist['id_quotation'] == $idQuotation) {
                            array_push($tempIndepend, $prodlist['descuento']);
                        }
                    }
                }
            }

            // Agrupa los porcentajes por convenio iguales al concepto y obtiene el mayor
            if (sizeof($tempConvenioEqual) > 0) {
                rsort($tempConvenioEqual); // ordena de mayor a menor los descuentos de la lista actual agregada segun el producto
                $percentConvenioEq = $tempConvenioEqual[0]; // Obtiene el mayor decuento acumulado de la lista actual de la negociacion segun el producto // Descuento 1
            } else {
                $percentConvenioEq = 0;
            }

            array_push($convenioConcept, $percentConvenioEq);
            // Agrupa los porcentajes por convenio y obtiene el mayor
            if (sizeof($tempConvenio) > 0) {
                rsort($tempConvenio); // ordena de mayor a menor los descuentos de la lista actual agregada segun el producto
                $percentConvenio = $tempConvenio[0]; // Obtiene el mayor decuento acumulado de la lista actual de la negociacion segun el producto // Descuento 1
            } else {
                $percentConvenio = 0;
            }

            array_push($convenioNoConcept, $percentConvenio);

            // Agrupa los porcentajes independientes y los suma
            if (sizeof($tempIndepend) > 0) {
                $percentIndepen = array_sum($tempIndepend); // Obtiene el mayor decuento acumulado de la lista actual de la negociacion segun el producto // Descuento 1
            } else {
                $percentIndepen = 0;
            }

            array_push($independiente, $percentIndepen);
        }

        $result['maxConcept']   = array_sum($convenioConcept);
        $percentNoConcepts      = array_sum($convenioNoConcept);
        $percentIndep           = array_sum($independiente);

        $result['maxNoConcept'] = $percentNoConcepts + $percentIndep;
        return $result; // Descuento total de lista

    }

    public function getMaxPercentDatabaseNoConvenio($idClient, $idProduct, $idConcept, $listDescAcumulated, $idQuotation)
    { // Obtiene el descuento acumulado en base de datos x independiente

        $discountMaxEscala = NegotiationDetails::orderBy('discount', 'DESC') // Trae el mas alto de la escala
            ->where('id_product', $idProduct)
            ->where('id_client', $idClient)
            ->where('discount_type', "escala")
            ->where('id_quotation', $idQuotation)
            ->where('is_valid', 1)
            ->whereHas('negotiation', function ($query) {
                return $query->where('status_id', '<=', 6); // Se ajusto que consultara si la negociacion estaba vigente
            })
            ->first();

        if ($discountMaxEscala) {
            $maxEscala = $discountMaxEscala->discount;
        } else {
            $maxEscala = 0;
        }

        //return $maxEscala;

        $discountIndependiente = NegotiationDetails::where('id_product', $idProduct) // Trae la suma de los descuentos tipo independiente
            ->where('id_client', $idClient)
            ->where('discount_type', "independiente")
            ->where('id_quotation', $idQuotation)
            ->where('is_valid', 1)
            ->whereHas('negotiation', function ($query) {
                return $query->where('status_id', '<=', 6); // Se ajusto que consultara si la negociacion estaba vigente
            })
            ->get()
            ->sum('discount');

        if ($discountIndependiente) {
            $descIndependiente = $discountIndependiente;
        } else {
            $descIndependiente = 0;
        }

        // return $descIndependiente;

        // Suma de los descuento mas altod por cada tipo de convenio
        $concepts = NegotiationConcepts::orderBy('id_negotiation_concepts', 'ASC')->pluck('id_negotiation_concepts');
        $descConvenios = [];
        //return $listDescAcumulated;
        foreach ($concepts as $concept) {
            $tempConvenio = [];
            $discountMaxConvenio = NegotiationDetails::orderBy('discount', 'DESC')
                ->where('id_product', $idProduct) // Trae la suma de los descuentos tipo independiente
                ->where('id_client', $idClient)
                ->where('id_concept', $concept)
                ->where('discount_type', "convenio")
                ->where('id_quotation', $idQuotation)
                ->where('is_valid', 1)
                ->whereHas('negotiation', function ($query) {
                    return $query->where('status_id', '<=', 6); // Se ajusto que consultara si la negociacion estaba vigente
                })
                ->first();
            if ($discountMaxConvenio) {
                if (sizeof($listDescAcumulated) > 0) {
                    foreach ($listDescAcumulated as $key => $prodlist) {
                        if ($prodlist['idProduct'] == $idProduct && $prodlist['idConcept'] == $concept && $prodlist['tipoDescuento'] == 'convenio') {
                            array_push($tempConvenio, $prodlist['descuento']);
                            //$discountList = $prodlist['descuento'];
                        }
                    }
                } else {
                    $discountList = 0;
                }

                if (sizeof($tempConvenio)) {
                    rsort($tempConvenio); // ordena de mayor a menor los descuentos de la lista actual agregada segun el producto
                    $discountList = $tempConvenio[0];
                } else {
                    $discountList = 0;
                }

                $discountDB = $discountMaxConvenio->discount;

                if ($discountList >= $discountDB) {
                    array_push($descConvenios, $discountList);
                } else {
                    array_push($descConvenios, $discountDB);
                }
            }
        }

        //return $descConvenios;

        if (sizeof($descConvenios) > 0) {
            $discountConvenios = array_sum($descConvenios);
        } else {
            $discountConvenios = 0;
        }

        //return $discountConvenios;

        $result = $maxEscala + $descIndependiente + $discountConvenios;
        return $result;
    }

    public function getMaxPercentListNoConvenio($idProduct, $listDescAcumulated, $idConcept, $idQuotation)
    { // Obtiene el descuento acumulado en lista x independiente
        // Maximo descuento de la lista actual de productos
        $concepts = NegotiationConcepts::orderBy('id_negotiation_concepts', 'ASC')->pluck('id_negotiation_concepts');
        $allConcepts = [];
        foreach ($concepts as $key => $concept) {
            $tempIndepend = [];
            $tempConvenio = [];
            foreach ($listDescAcumulated as $prodlist) {
                if ($prodlist['idProduct'] == $idProduct) {
                    if ($prodlist['idConcept'] == $concept && $prodlist['tipoDescuento'] == 'independiente') {
                        array_push($tempIndepend, $prodlist['descuento']);
                    }
                    if ($prodlist['idConcept'] == $concept && $prodlist['tipoDescuento'] == 'convenio') {
                        array_push($tempConvenio, $prodlist['descuento']);
                    }
                }
            }

            if (sizeof($tempConvenio) > 0) {
                rsort($tempConvenio); // ordena de mayor a menor los descuentos de la lista actual agregada segun el producto
                $percentConvenio = $tempConvenio[0]; // Obtiene el mayor decuento acumulado de la lista actual de la negociacion segun el producto // Descuento 1
            } else {
                $percentConvenio = 0;
            }

            // Agrupa los porcentajes independientes y los suma
            if (sizeof($tempIndepend) > 0) {
                $percentIndepen = array_sum($tempIndepend); // Obtiene el mayor decuento acumulado de la lista actual de la negociacion segun el producto // Descuento 1
            } else {
                $percentIndepen = 0;
            }

            array_push($allConcepts, $percentIndepen + $percentConvenio);
        }

        $percentConcepts = number_format(array_sum($allConcepts), 2, ".", ",");

        $totalDescList = $percentConcepts;

        return $totalDescList; // Descuento total de lista
    }

    public function getMaxPercentListEscalas($idProduct, $listDescAcumulated, $idQuotation) // Maximo descuento de lista por escalas
    {

        $escalas = [];

        foreach ($listDescAcumulated as $key => $prodlist) {
            if ($prodlist['visible'] == "SI" && $prodlist['idProduct'] == $idProduct && $prodlist['idConcept'] == 0 && $idQuotation == $prodlist['idQuotation']) {
                array_push($escalas, number_format($prodlist['descuento'], 2, ".", ","));
            }
        }

        if (sizeof($escalas) > 0) {
            rsort($escalas); // ordena de mayor a menor los descuentos de la lista actual agregada segun el producto
            $percentEscala = $escalas[0]; // Obtiene el mayor decuento acumulado de la lista actual de la negociacion segun el producto // Descuento 1
        } else {
            $percentEscala = 0;
        }

        return $percentEscala;
    }

    public function getNegotiData(Request $request)
    {
        $idNego = $request->idNego;
        $negociacion =  Negotiation::where('id_negotiation', $idNego)->with('cliente', 'channel')->first();
        $time = strtotime($negociacion->negotiation_date_ini);
        $dateIni = date('Y-m-d', $time);
        $time = strtotime($negociacion->negotiation_date_end);
        $dateEnd = date('Y-m-d', $time);
        $negociacion->negotiation_date_ini = $dateIni;
        $negociacion->negotiation_date_end = $dateEnd;
        return $negociacion;
    }

    public static function getCountPre() //1,0,1
    {
        $negotiations = Negotiation::where('status_id', 1)
        ->where('pre_approved', 0)
        ->count();
        return $negotiations;
    }

    public static function getCountAutorizaciones() //1,2
    {
        $counter = 0;
        $negotiations = Negotiation::whereHas('approving', function ($query) {
            $query->where('user_id', Auth::user()->id)
            ->whereNull('answer');
        })
        ->whereIn('status_id', [3, 4, 5])
        ->get();

        $lvl = auth()->user()->authlevel;
        if($lvl > 2){
            $users = User::where('authlevel', $lvl - 1)->pluck('id');
            foreach ($negotiations as $nego) {
                $negoStatus = NegotiationApprovers::where('negotiation_id',$nego->id_negotiation)
                ->whereIn('user_id', $users)
                ->latest()
                ->first();
                if($negoStatus->answer != null){
                    $counter = $counter + 1;
                }
            }
            return $counter;
        }else{
            return $negotiations->count();
        }

    }

    // Create PDF for negotiations
    public function createPDF(Request $request, $id)
    {
        $valid = 1;
        $escalas = false;
        $quota_consecutive = [];
        $state = $request->state;
        $isEditor = auth()->user()->hasRole('adminventa');
        $negociacion = Negotiation::where('id_negotiation', $id)->with('negodetails', 'status', 'users', 'creator', 'cliente', 'approving')->first();
        if ($isEditor == false){
            $state = 'true';
        }

        $products = NegotiationDetails::where('id_negotiation', $id)
        ->where('is_valid', $valid)
        ->with('product', 'quotation')
        ->get();

        $products_no_vol = NegotiationDetails::where('id_negotiation', $id)
            ->where('id_concept', 3)
            ->where('suj_volumen', 'NO')
            ->where('visible', 'SI')
            ->where('is_valid', $valid)
            ->with('product', 'quotation')
            ->get();

        $nego_especiales = NegotiationDetails::where('id_negotiation', $id)
            ->where('id_concept', 6)
            ->orWhere('id_concept', 8)
            ->orWhere('id_concept', 10)
            ->where('suj_volumen', 'NO')
            ->where('visible', 'SI')
            ->where('is_valid', $valid)
            ->with('product', 'quotation')
            ->get();

        $packx3 = NegotiationDetails::where('id_negotiation', $id)
            ->where('id_concept', 2)
            ->where('suj_volumen', 'NO')
            ->where('visible', 'SI')
            ->where('is_valid', $valid)
            ->with('product', 'quotation')
            ->get();

        $products_info = NegotiationDetails::where('id_negotiation', $id)
            ->where('id_concept', 1)
            ->where('suj_volumen', 'NO')
            ->where('visible', 'SI')
            ->where('is_valid', $valid)
            ->with('product', 'quotation')
            ->get();

        $groupProds = DB::table('nvn_negotiations_details')
            ->select('nvn_negotiations_details.id_product', 'nvn_products.id_prod_line', 'nvn_products.prod_name', 'nvn_brands.brand_name')
            ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_negotiations_details.id_product')
            ->join('nvn_brands', 'nvn_brands.id_brand', '=', 'nvn_products.id_brand')
            ->where('nvn_negotiations_details.id_negotiation', $id)
            ->where('nvn_negotiations_details.is_valid', $valid)
            ->groupBy('nvn_negotiations_details.id_product', 'nvn_products.id_prod_line', 'nvn_products.prod_name', 'nvn_brands.brand_name')
            ->orderBy('nvn_products.id_prod_line', 'ASC')
            ->get();

        // Productos x Escala
        $brandGroup = DB::table('nvn_negotiations_details')
            ->select('nvn_products.id_prod_line', 'nvn_products.id_brand', 'nvn_brands.brand_name', 'nvn_products.id_prod_line')
            ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_negotiations_details.id_product')
            ->join('nvn_brands', 'nvn_brands.id_brand', '=', 'nvn_products.id_brand')
            ->where('nvn_negotiations_details.id_negotiation', $id)
            ->where('nvn_negotiations_details.id_concept', 0)
            ->where('nvn_negotiations_details.is_valid', $valid)
            ->groupBy('nvn_products.id_prod_line', 'nvn_products.id_brand', 'nvn_brands.brand_name')
            ->orderBy('nvn_products.id_prod_line', 'ASC')
            ->get();

        $lines = Product_Line::orderBy('id_line', 'ASC')->get();

        foreach ($lines as $key => $line) {
            ${'prods' . $line->id_line} = [];
            ${'idProds' . $line->id_line} = [];
        }

        foreach ($brandGroup as $key => $brand) {

            $prod = NegotiationDetails::select('nvn_negotiations_details.observations', 'nvn_negotiations_details.discount', 'nvn_products.id_brand', 'nvn_brands.brand_name')
                ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_negotiations_details.id_product')
                ->join('nvn_brands', 'nvn_brands.id_brand', '=', 'nvn_products.id_brand')
                ->where('nvn_products.id_brand', $brand->id_brand)
                ->where('nvn_negotiations_details.id_negotiation', $id)
                ->where('nvn_negotiations_details.id_concept', 0)
                ->where('nvn_negotiations_details.visible', "SI")
                ->where('nvn_negotiations_details.is_valid', $valid)
                ->groupBy('nvn_negotiations_details.observations', 'nvn_negotiations_details.discount', 'nvn_products.id_brand', 'nvn_brands.brand_name')
                ->orderBy('nvn_negotiations_details.discount')
                ->get();

            if ($prod) {
                $escalas = true;
            }

            $prod2 = DB::table('nvn_negotiations_details')
                ->select(
                    'nvn_negotiations_details.observations',
                    'nvn_negotiations_details.discount',
                    'nvn_products.id_brand',
                    'nvn_brands.brand_name',
                    'nvn_negotiations_details.id_product',
                    'nvn_products.prod_package_unit',
                    'id_measure_unit'
                )
                ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_negotiations_details.id_product')
                ->join('nvn_brands', 'nvn_brands.id_brand', '=', 'nvn_products.id_brand')
                ->where('nvn_products.id_brand', $brand->id_brand)
                ->where('nvn_negotiations_details.id_negotiation', $id)
                ->where('nvn_negotiations_details.is_valid', $valid)
                ->groupBy(
                    'nvn_negotiations_details.observations',
                    'nvn_negotiations_details.discount',
                    'nvn_products.id_brand',
                    'nvn_brands.brand_name',
                    'nvn_negotiations_details.id_product',
                    'nvn_products.prod_package_unit',
                    'id_measure_unit'
                )
                ->orderBy('nvn_negotiations_details.discount')
                ->get();

            array_push(${'prods' . $brand->id_prod_line}, $prod);
            array_push(${'idProds' . $brand->id_prod_line}, $prod2);
        }

        if (!isset($prods7)) {
            $prods7 = '0';
            $idProds7 = '0';
        }

        $location = Location::find($negociacion->cliente->id_city);

        $meses      = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $fecha      = Carbon::parse(strtotime($negociacion->negotiation_date_ini));
        $mes        = $meses[($fecha->format('n')) - 1];
        $desde      = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

        $fecha      = Carbon::parse(strtotime($negociacion->negotiation_date_end));
        $mes        = $meses[($fecha->format('n')) - 1];
        $hasta      = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

        $fecha      = Carbon::parse(Date::now()->format('l j F Y H:i:s'));
        $mes        = $meses[($fecha->format('n')) - 1];
        $fechadia   = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

        if (!empty($products[0])) {
            $fechaC     = Carbon::parse(strtotime($products[0]->quotation->quota_date_ini));
            $cotYear    = $fechaC->format('Y');
        } else {
            $fechaC     = '';
            $cotYear    = 'Y';
        }
        // Users signatures
        $camSign    = User::where('id', $negociacion->cliente->id_diab_contact)->first();
        $temp = [];
        foreach ($products as $product) {
            if (!in_array($product->quotation->quota_consecutive, $temp)) {
                array_push($quota_consecutive, $product->quotation);
                array_push($temp, $product->quotation->quota_consecutive);
            }
        }


        if (isset($request->aditionalData)) {
            $aditionalData = $request->aditionalData;
            $negociacion->update(['pdf_content' => $aditionalData]);
        } else {
            $aditionalData = $negociacion->pdf_content;
        }

        $approving = NegotiationApprovers::where('negotiation_id', $id)
            ->whereNotNull('answer')
            ->whereHas('approversUser', function ($query) {
                $query->where('is_authorizer', 1);
            })->distinct()->get(['user_id', 'answer']);

        if ($state == 'false') {
            return view('admin.negotiations.template.pdfhtml', compact(
                'negociacion',
                'approving',
                'aditionalData',
                'products',
                'location',
                'desde',
                'hasta',
                'fechadia',
                'prods1',
                'prods2',
                'prods4',
                'prods5',
                'prods6',
                'prods7',
                'idProds1',
                'idProds2',
                'idProds4',
                'idProds5',
                'idProds6',
                'idProds7',
                'lines',
                'groupProds',
                'brandGroup',
                'products_no_vol',
                'products_info',
                'cotYear',
                'camSign',
                'quota_consecutive',
                'escalas',
                'nego_especiales',
                'packx3'
            ));
        } else {
            if (!empty($negociacion)) {
                $pdf = PDF::loadView('admin.negotiations.template.pdf', compact(
                    'negociacion',
                    'approving',
                    'aditionalData',
                    'products',
                    'location',
                    'desde',
                    'hasta',
                    'fechadia',
                    'prods1',
                    'prods2',
                    'prods4',
                    'prods5',
                    'prods6',
                    'prods7',
                    'idProds1',
                    'idProds2',
                    'idProds4',
                    'idProds5',
                    'idProds6',
                    'idProds7',
                    'lines',
                    'groupProds',
                    'brandGroup',
                    'products_no_vol',
                    'products_info',
                    'cotYear',
                    'camSign',
                    'quota_consecutive',
                    'escalas',
                    'nego_especiales',
                    'packx3'
                ))->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->download('NOVO-' . $negociacion->negotiation_consecutive . '.pdf');
            }
        }
    }

    public static function negotiationSendEmail(Request $request)
    {
        $id             = $request->idNego;
        $negotiation    = Negotiation::where('id_negotiation', $id)->with('negodetails', 'status', 'users', 'creator', 'cliente')->first();
        $email          = $request->email;
        $from           = Auth::user()->email;
        $name           = Auth::user()->name;
        Mail::send('admin.negotiations.email.negoemail', compact('negotiation'), function ($msj) use ($email, $from, $name, $negotiation) {
            $msj->subject('Negociación '. $negotiation->negotiation_consecutive .' enviada por ' . $name . ' desde Novo Nordisk CAM Tool');
            $msj->cc($from);
            $msj->from($from);
            $msj->to($email);
        });
        if (count(Mail::failures()) > 0) {
            echo "There was one or more failures. They were: <br />";
            foreach (Mail::failures() as $email_address) {
                echo " - $email_address <br />";
            }
        } else {
            return redirect()->route('negociaciones.index')->with('success', 'Negociación enviada exitosamente');
        }
    }


    public function cancelNego(Request $request)
    {
        $id = $request->id_negotiation;
        $negotiation = Negotiation::find($id);

        if ($negotiation) {
            $negotiation->status_id = 8;
            $negotiation->comments = $request->comments;
            if ($negotiation->update()) {
                $input = [
                    'is_valid'  =>   0,
                ];
                NegotiationComments::create([
                    'id_negotiation'  => $id,
                    'created_by'    => Auth()->user()->id,
                    'type_comment'  => 'Anulada',
                    'text_comment'  => $request->comments,
                ]);
                NegotiationDetails::where('id_negotiation', $id)->update($input);
                return redirect()->route('negociaciones.index')->with('info', 'Negociación anulada satisfactoriamente');
            }
        }
    }



    /*** Remove the specified resource from storage.*/
    public function destroy($id)
    {
        Negotiation::find($id)->delete();
        $isEditor = auth()->user()->hasPermissionTo('negociaciones.destroy');
        if ($isEditor) {
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }
}

