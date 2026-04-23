<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

use App\Quotation;
use App\QuotationxStatus;
use App\Status;
use App\Client;
use App\Channel_Types;
use App\DocFormat;
use App\DocFormatType;
use App\Location;
use App\User;
use App\Product;
use App\PaymentTerms;
use App\QuotationDetails;
use App\Product_AuthLevels;
use App\ProductxPrices;
use App\QuotationxDocs;
use App\Events\OrderNotificationsEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Caffeinated\Shinobi\Middleware;
use DateTime;
use App\Notifications;
use App\QuotationApprovers;
use App\QuotationxComments;
use App\Traits\GenericTrait;
use Illuminate\Support\Facades\URL;
use Caffeinated\Shinobi\Models\Role;
use League\CommonMark\Extension\SmartPunct\Quote;
use PDF;


//use RealRashid\SweetAlert\Facades\Alert;

class QuotationsController extends Controller
{

    use GenericTrait;

    public function index(Request $request)
    {
        Artisan::call('update:quotation-status-defeated');

        $filter = $request->query('filter');
        $quantity = $request->query('quantity');
        $roles = auth()->user()->roles;
        $rol = $roles[0]->slug;

        Quotation::updateQuotationsbyDate();
        Quotation::updateQuotationsbyProducts();

        $isEditor = auth()->user()->hasPermissionTo('cotizaciones.index');
        if ($isEditor) {
            $estado = 0;
            $quotations = [];
            $quotations = $this->queryWithFilter($rol, $filter, $quantity);
            return view('admin.quotations.index', compact('quotations', 'estado', 'rol', 'quantity', 'filter'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function indexFilter($estado, $quantity)
    {
        $filter = "";
        $roles = auth()->user()->roles;
        $rol = $roles[0]->slug;
        $quotations = [];
        if ($rol == "cam" || $rol == "admin_venta") {
            $quotations = Quotation::sortable(['id_quotation' => 'DESC'])
            ->whereHas('cliente', function ($query) {
                $query->where('id_diab_contact', auth()->user()->id);
            })
            ->whereIn('status_id', [1,2,3,4,5])
            ->orderBy('id_quotation', 'DESC')
            ->with('cliente', 'users', 'channel', 'status')
            ->paginate(20);
        } else {
            $quotations = Quotation::sortable(['id_quotation' => 'DESC'])
            ->whereIn('status_id', [3,4,5])
            ->orderBy('id_quotation', 'DESC')
            ->with('cliente', 'channel', 'status')
            ->paginate(20);
        }
        return view('admin.quotations.index', compact('quotations', 'estado', 'rol', 'quantity','filter'));
    }

    public function queryWithFilter($rol, $filter, $quantity)
    {
        if (!empty($filter)) {
            $date = date('Y-m-d',strtotime($filter));
            $quotations = Quotation::sortable(['id_quotation' => 'DESC'])->with('cliente', 'channel', 'status');
            if($rol == "cam"){
                $quotations =  $quotations->where('created_by', auth()->user()->id);
            }
            $quotations =  $quotations->where( function ( $query ) use ($filter, $date)  {
                $query->orWhereHas('channel', function ($query) use ($filter) {
                    $query->where('channel_name', 'ILIKE', '%' . $filter . '%');
                });
                $query->orWhereHas('creator', function ($query) use ($filter) {
                    $query->where('name', 'ILIKE', '%' . $filter . '%');
                });
                $query->orWhereHas('cliente', function ($query) use ($filter) {
                    $query->where('client_name', 'ILIKE', '%' . $filter . '%');
                });
                $query->orWhereDate('quota_date_ini','=',$date)
                ->orWhereDate('quota_date_end','=',$date)
                ->orWhere('quota_consecutive', 'ILIKE', '%' . $filter . '%');
            });
            $quotations =  $quotations ->paginate($quantity);
        } else {
            $quotations = Quotation::sortable(['id_quotation' => 'DESC'])->with('cliente', 'channel', 'status');
            if($rol == "cam"){
                $quotations =  $quotations->where('created_by', auth()->user()->id);
            }
            $quotations = $quotations->paginate($quantity);
        }
        return $quotations;
    }

    public function create()
    {
        $isEditor = auth()->user()->hasPermissionTo('cotizaciones.create');
        if ($isEditor) {
            $roles = auth()->user()->roles;
            $rol = $roles[0]->slug;

            if ($roles[0]->slug == "cam") {
                $clientes = Client::where('id_diab_contact', auth()->user()->id)->orderBy('client_name', 'ASC')->where('active', 1)->pluck('client_name', 'id_client');
            } else {
                $clientes = Client::orderBy('client_name', 'ASC')->where('active', 1)->pluck('client_name', 'id_client');
            }

            $usuarios = [];
            $allUser = User::orderBy('name', 'ASC')->with('roles')->get();

            foreach ($allUser as $key => $user) {
                if (sizeof($user->roles) > 0) {
                    if ($user->roles[0]->slug == 'cam') {
                        array_push($usuarios, $user);
                    }
                }
            }

            $canales = Channel_Types::all();
            $locaciones = Location::all();
            $autorizador = User::orderBy('id', 'ASC')->pluck('name', 'id');
            $productos = Product::orderBy('prod_name', 'ASC')->pluck('prod_name', 'id_product');
            $forma_pagos = PaymentTerms::all();
            return view('admin.quotations.create', compact('clientes', 'canales', 'locaciones', 'autorizador', 'productos', 'forma_pagos', 'usuarios', 'rol'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function store(Request $request)
    {
        $hasFile = $request->hasFile('docs');

        //dd($request->hasFile('docs'));

        $isEditor = auth()->user()->hasPermissionTo('cotizaciones.create');
        if ($isEditor) {
            $msg = "";
            $status = 1;
            $thisyear = now()->year;
            $lastQuotation = Quotation::whereYear('created_at', $thisyear)->orderBy('id_quotation', 'desc')->first();
            if ($lastQuotation) {
                $quota_num = intval($lastQuotation->quota_number) + 1;
            } else {
                $quota_num = 1;
            }
            $quota_consecutive = 'C-' . $quota_num . '-' . $thisyear;

            $quota =  new Quotation();
            $quota->id_client           = $request->id_client;
            $quota->id_city             = $request->id_city;
            $quota->id_authorizer_user  = $request->id_authorizer_user;
            $quota->id_channel          = $request->id_client_channel;
            $quota->quota_value         = round($request->quota_value);
            $quota->id_auth_level       = $request->id_auth_level;
            $quota->quota_date_ini      = $request->quota_date_ini;
            $quota->quota_date_end      = $request->quota_date_end;
            $quota->quota_number        = $quota_num;
            $quota->quota_consecutive   = $quota_consecutive;
            $quota->comments            = $request->comment;

            if ($request->id_auth_level <= 1) {
                $status                 = 6;
            }
            $quota->is_authorized   = $status;
            $quota->status_id       = $status;

            if ($request->id_cam != "") {
                $quota->created_by      = $request->id_cam;
            } else {
                $quota->created_by      = Auth::user()->id;
            }
            $statusName = Status::getName($status)->pluck('status_name')->first();

            if ($quota->save()) {
                // Save Quotation Files
                $hasFile = $request->hasFile('docs');
                if ($hasFile) {
                    $files = $request->file('docs');
                    $folder = $quota->id_quotation;
                    QuotationxDocs::storeFiles($files, $folder);
                }

                $products = $request->id_product;

                //Save quotation Products
                if (count($products) > 0) {
                    $msg = $this->storeQuotationProducts($request, $products, $quota);
                }

                // Update the old products in another quotas
                Quotation::updateQuotationsbyApprovals($quota->id_quotation,$status);

                // Autorizers and Notification creation
               // $this->storeQuotationStatus($quota,$status,$quota->id_quotation,$statusName,$request->comment);

                return redirect()->route('cotizaciones.index')->with('info', 'Cotización creada satisfactoriamente');
            } else {
                return redirect()->route('cotizaciones.index')->with('error', 'Existio un problema al crear su cotización');
            }
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    // Save products for Quotation
    public function storeQuotationProducts(Request $request, $products, $quota)
    {
        $client = Client::where('id_client', $request->id_client)->first();

        $client_payterm         = $client->id_payterm;
        $id_product             = $request->id_product;
        $id_payterm             = $client_payterm;
        $quantity               = $request->quantity;
        $pay_discount           = $request->pay_discount;
        $prod_uminima           = $request->prod_uminima;
        $id_total_product       = $request->id_total_product;
        $id_prod_auth_level     = $request->id_prod_auth_level;
        $prod_cost              = $request->prod_cost;
        $authlevel              = $request->authlevel;
        $percent_discount       = $request->percent_discount;
        foreach ($products as $key => $prod) {
            $details = new QuotationDetails();
            $details->quotation()->associate($quota);
            $details->id_client             = $request->id_client;
            $details->id_product            = $id_product[$key];
            try {
                $details->id_payterm        = $client_payterm;
            } catch (NotFoundException $e) {
                $details->id_payterm        = $client_payterm;
            }
            $details->quantity              = $quantity[$key];
            $details->prod_cost             = round($prod_cost[$key]);
            $details->time_discount         = $percent_discount[$key];
            $details->pay_discount          = $pay_discount[$key];
            $details->price_uminima         = $prod_uminima[$key];
            $details->totalValue            = round($id_total_product[$key]);
            $details->id_prod_auth_level    = $id_prod_auth_level[$key];
            $details->authlevel             = $authlevel[$key];
            if ($request->id_auth_level <= 1 || $request->id_auth_level = "") {
                $details->is_valid              = 6;
                $msg = "Se ha creado la cotización ";
            } else {
                $details->is_valid              = 1;
                $msg = "Se espera la aprobación de la cotización ";
            }
            $details->save();
        }

        return $msg;
    }

    // Show info quotation
    public function show($id)
    {
        $roles = auth()->user()->roles;
        $rol = $roles[0]->slug;
        $isEditor = auth()->user()->hasPermissionTo('cotizaciones.show');
        if ($isEditor) {
            $quotation = Quotation::where('id_quotation', $id)->with('quotadetails', 'status', 'users','usercomments')->first();
            //dd($quotation->status->status_name);
            $autorizador = User::where('is_authorizer', 1)->where('id', $quotation->id_authorizer_user)->first();
            //$code_statu = Quotation::where('id_quotation', $id)->get('status_id');
            $productos = QuotationDetails::where('id_quotation', $id)
                ->join('nvn_products', 'nvn_quotations_details.id_product', '=', 'nvn_products.id_product')
                ->orderBy('nvn_products.prod_name', 'ASC')->select('nvn_quotations_details.*')
                ->get();

            $documentos = QuotationxDocs::where('id_quotation', $id)->get();
            if (!empty($autorizador)) {
                $autorizador = $autorizador->name;
            } else {
                $autorizador = "";
            }

            $forma_pagos = PaymentTerms::all();
            $location = Location::find($quotation->cliente->id_city);
            return view('admin.quotations.show', compact('quotation', 'productos', 'forma_pagos', 'autorizador', 'location', 'rol', 'documentos'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function edit($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('cotizaciones.edit');
        if ($isEditor) {
            $quotation = Quotation::where('id_quotation', $id)->with('quotadetails', 'status', 'city', 'docs', 'usercomments', 'approving')->first();
            $productos = Product::orderBy('prod_name')->pluck('prod_name', 'id_product');
            return view('admin.quotations.edit', compact('quotation', 'productos'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function editdate($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('cotizaciones.edit');
        if ($isEditor) {
            $quotationdate = Quotation::find($id);
            // dd($quotationdate);
            return view('admin.quotations.editdate', compact('quotationdate'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function preaprobado($id)
    {
        $quota = Quotation::findOrFail($id);
        $quota->status_id = 2;
        $quota->save();
        $dt = Carbon::now();
        $userid = Auth::user()->id;
        //$register = Quotation::select('id')->where('id', $id)->get();
        $activityQuo = [
            'status_id' => 2,
            'quotation_id' => $id,
            'user_id' => $userid,
            'updated_at' => $dt
        ];
        Quotationxstatus::where('nvn_quotationxstatus')->insert($activityQuo);
        return redirect()->route('cotizaciones.show', $id)->with('Cotización preaprobada exitosamente');
    }

    public function updatedate(Request $request) // Update only the quotation date.
    {
        $users = [];
        $isEditor = auth()->user()->hasPermissionTo('cotizaciones.edit');
        if ($isEditor) {
            $quota = Quotation::findOrFail($request->idQuota);
            $quota->quota_date_ini      = $request->quota_date_ini;
            $quota->quota_date_end      = $request->quota_date_end;
            $quota->status_id           = '1';
            if ($quota->update()) {
                $quotationdatedetails = QuotationDetails::where('id_quotation', $request->idQuota)->get();
                foreach ($quotationdatedetails as $key => $value) {
                    $value->is_valid = 1;
                    $value->update();
                }

                $users_notified = Role::with('users')->where('slug', 'admin_venta')->get();
                foreach ($users_notified as $user) {
                    array_push($users, $user->users[0]->id);
                }

                $status = 1;
                $statusName = Status::getName($status)->pluck('status_name')->first();

                $comments = "Se ha modificado la fecha de la cotización ". $quota->quota_consecutive;
                $this->updateQuotationStatus($quota,$status,$users,$quota->id_quotation,$statusName,$comments);

                return redirect()->route('cotizaciones.index')->with('info', 'Fecha actualizada exitosamente');
            } else {
                return redirect()->route('cotizaciones.index')->with('error', 'No se pudo actualizar la fecha, verifique los datos proporcionados');
            }
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function update(Request $request, $id)
    {
        //dd($request);
        $users = [];
        $status = 1;
        if ($request->id_auth_level <= 1) {
            $status                 = 4;
        }
        $isEditor = auth()->user()->hasPermissionTo('cotizaciones.edit');
        if ($isEditor) {
            $products = $request->id_product;

            $quota = Quotation::where('id_quotation', $id)->first();
            $quota->quota_value             = $request->quota_value;
            $quota->id_authorizer_user      = $request->id_authorizer_user;
            $quota->id_auth_level           = $request->id_auth_level;
            $quota->is_authorized   = $status;
            $quota->status_id       = $status;

            if ($quota->update()) {

                $hasFile = $request->hasFile('docs');
                if ($hasFile) {
                    $files = $request->file('docs');
                    $folder = $quota->id_quotation;
                    QuotationxDocs::storeFiles($files, $folder);
                }

                if (sizeof($products) > 0) {
                    $id_product             = $request->id_product;
                    $id_payterm             = $request->pay_term;
                    $quantity               = $request->quantity;
                    $pay_discount           = $request->pay_discount;
                    $prod_uminima           = $request->prod_uminima;
                    $id_total_product       = $request->id_total_product;
                    $id_prod_auth_level     = $request->id_prod_auth_level;
                    $prod_cost              = $request->prod_cost;
                    $authlevel              = $request->authlevel;
                    $percent_discount       = $request->percent_discount;

                    $deleteProducts = QuotationDetails::where('id_quotation', $id)->delete();

                    foreach ($quantity as $key => $product) {

                        $details = new QuotationDetails();
                        $details->quotation()->associate($quota);
                        $details->id_client             = $request->id_client;
                        $details->id_product            = $id_product[$key];
                        $details->id_payterm            = $id_payterm[$key];
                        $details->quantity              = $quantity[$key];
                        $details->prod_cost             = round($prod_cost[$key]);
                        $details->time_discount         = $percent_discount[$key];
                        $details->pay_discount          = $pay_discount[$key];
                        $details->price_uminima         = $prod_uminima[$key];
                        $details->totalValue            = round($id_total_product[$key]);
                        $details->id_prod_auth_level    = $id_prod_auth_level[$key];
                        $details->authlevel             = $authlevel[$key];
                        if ($request->id_auth_level <= 1 || $request->id_auth_level = "") {
                            $details->is_valid              = 1;
                        } else {
                            $details->is_valid              = 2;
                        }
                        $details->save();
                    }
                }
                // Add comments
                $comments = $request->comment;
                $statusName = Status::getName($status)->pluck('status_name')->first();

                $users_notified = Role::with('users')->where('slug', 'admin_venta')->get();

                foreach ($users_notified as $user) {
                    array_push($users, $user->users[0]->id);
                }

                // Autorizers and Notification creation
                $this->updateQuotationStatus($quota,$status,$users,$id,$statusName,$comments);
            }
            //$quotation->update(request()->all());
            return redirect()->route('cotizaciones.index', $quota->id_quotation)->with('info', 'Cotización actualizada exitosamente');
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function destroy($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('cotizaciones.destroy');
        if ($isEditor) {
            // Elimina aprobadores
            QuotationApprovers::where('quotation_id',$id)->delete();
            QuotationxStatus::where('quotation_id',$id)->delete();
            Quotation::find($id)->delete();
            return redirect()->route('cotizaciones.index')->with('info', 'Cotización eliminada satisfactoriamente');
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function getPreviousProduct(Request $request)
    {

        $queryProduct = QuotationDetails::where('id_client', $request->idClient)
            ->where('id_product', $request->idProduct)
            ->latest('id_quota_det')
            ->first();
        // dd($request->idProduct);
        if (strcmp($request->channel, "COMERCIAL") == 0) {
            $pricetype = "v_commercial_price";
        } else {
            $pricetype = "v_institutional_price";
        }

        $dateIni = new DateTime($request->dateIni);
        $dateIni->format('Y-m-d H:i:s');
        $dateEnd = new DateTime($request->dateIni);
        $dateEnd->format('Y-m-d H:i:s');

        $query = ProductxPrices::where('id_product', $request->idProduct)
            ->where('prod_valid_date_ini', '<=', $dateIni)
            ->where('prod_valid_date_end', '>=', $dateEnd)
            ->where('active', 1)->with('product')
            ->first();


        $client = Client::where('id_client', $request->idClient)->pluck('id_payterm');
        $payterm = $client[0];
        //return $queryProduct;
        if (empty($queryProduct)) {
            //return $client;
            $percent = PaymentTerms::where('id_payterms', $client)->pluck('payterm_percent');
            return array(
                "precio"        => $query[$pricetype],
                "precio_old"    => $query[$pricetype],
                "precio_com"    => $query[$pricetype],
                "unidades"      => $query->product->prod_package_unit,
                "id_payterm"    => $payterm,
                "id_formaPago"    => $payterm,
                "pay_discount"  => $percent[0],
                "time_discount" => $percent[0],
            );
        } else {
            $quota = Quotation::where('id_quotation', $queryProduct->id_quotation)->first();
            $result = array_add($queryProduct, 'precio', $query[$pricetype]);
            $result = array_add($queryProduct, 'precio_com', $query[$pricetype]);
            $result = array_add($queryProduct, 'precio_old', $queryProduct->totalValue);
            $result = array_add($queryProduct, 'unidades', $query->product['prod_package_unit']);
            $result = array_add($queryProduct, 'id_formaPago', $payterm);
            $result = array_add($queryProduct, 'desde', array(date('d-m-Y', strtotime($quota->quota_date_ini))));
            $result = array_add($queryProduct, 'hasta', array(date('d-m-Y', strtotime($quota->quota_date_end))));
            //return $queryProduct;
            return $result;
        }
        // return sizeof($queryProduct);
    }

    public function getHistoryProduct(Request $request)
    {
        $id_client = $request->idClient;
        $dateIni = new DateTime($request->dateIni);
        $dateIni->format('Y-m-d H:i:s');
        $dateEnd = new DateTime($request->dateEnd);
        $dateEnd->format('Y-m-d H:i:s');

        $queryProduct = QuotationDetails::where('id_client', $id_client)
            ->where('id_product', $request->idProduct)
            ->where(function($query){
                $query->where('is_valid', 1)
                ->orWhere('is_valid', 6);
            })
            ->whereHas('quotation', function ($query) use ($dateIni, $dateEnd) {
                return $query->where('quota_date_ini', '<=', $dateIni)
                ->where('quota_date_end', '>=', $dateEnd);
            })
            ->whereHas('quotation', function ($query) {
                return $query->where('is_authorized', '<', 6)
                ->orWhere('status_id','<=',6); // filtro de fecha inicial y cierre de negociacion
            })
            ->select('nvn_quotations_details.*')
            ->get();

        //return $queryProduct;

        if (sizeof($queryProduct) <= 0) {
            $found = false;
        } else {
            $found = true;
        }
        return array(
            "found" => $found
        );
    }

    // Veririca el calculo del precio al insertarlo en cotizaciones o al modificarlo y retorna los niveles
    public function calcProductQuota(Request $request)
    {

        $dateIni = new DateTime($request->dateIni);
        $dateIni->format('Y-m-d H:i:s');
        $dateEnd = new DateTime($request->dateEnd);
        $dateEnd->format('Y-m-d H:i:s');


        //$productPrice = ProductxPrices::where('id_product',$request->idProduct)->where('prod_valid_date_ini' ,'<=' ,$dateIni)->where('prod_valid_date_end' ,'>=' ,$dateIni)->where('active',1)->pluck($pricetype);
        $productPriceQuery =  ProductxPrices::where('id_product', $request->idProduct)
            ->where('prod_valid_date_ini', '<=', $dateIni)
            ->where('prod_valid_date_end', '>=', $dateIni)
            ->where('active', 1)->first();

        // return $productPriceQuery->version;
        $channel = $request->channelID;

        if ($channel ==  5) {
            $prodPrice = intval($productPriceQuery->v_commercial_price);
        } else {
            $prodPrice = intval($productPriceQuery->v_institutional_price);
        }

        $maxPrice = $productPriceQuery->prod_increment_max;


        $reguladoQuery = Product::where('id_product', $request->idProduct)->pluck('is_prod_regulated');
        $regulado = $reguladoQuery[0];
        $quotaPrice = $request->quotaPrice;

        /******************************************************************* */
        // OBTENEMOS EL PORCENTAJE DE DESCUENTO FINANCIERO DEL CLIENTE

        if ($request->payPercent) {
            $payPercent = PaymentTerms::where('id_payterms', $request->payPercent)->pluck('payterm_percent');
            $percentValue = $payPercent[0];
        } else {
            $percentValue = 0;
        }

        /***************************************************************************** */
        // CALCULAMOS EL PORCENTAJE DE DESCUENTO  QUE SE HACE VS EL PRECIO DE LISTA DEL PRODUCTO

        $priceDif = $prodPrice - $quotaPrice;
        if ($priceDif < 0) {
            $priceDif = $priceDif * -1;
        }
        $priceDisc = number_format(($priceDif / $prodPrice) * 100, 2, ".", ",");  // PORCENTAJE PARA MOSTRAR EN LA COTIZACION EN EL DESCUENTO APLICADO AL PRECIO


        /******************************************************************************* */
        // REALIZAMOS EL DESCUENTO FINANCIERO SOBRE EL PRECIO OBTENIDO DE LA COTIZACION

        if ($percentValue > 0) {
            $payDiscount =  $quotaPrice * ($percentValue / 100);  // descuento por pago
            $prodPrice = $prodPrice - $payDiscount;
        } else {
            $payDiscount = 0;
        }

        $quotaDesc = $quotaPrice - $payDiscount;


        /******************************************************************************* */
        //return $payDiscount;

        if ($quotaPrice  > $prodPrice + $payDiscount) {
            $textDescription = "Incremento";
            $descSymbol = "+";
        } else {
            $textDescription = "Descuento";
            $descSymbol = "-";
        }
        $msg = "empty";

        /******************************************************************************* */
        // REALIZAMOS LA VERIFICACION DE NIVEL ""ES PARA ASIGNAR EL NIVEL DE AUTORIZACION

        $queryLevel = Product_AuthLevels::orderBy('id_level_discount', 'ASC')
            ->where('id_product', $request->idProduct)
            ->where('id_dist_channel', $request->channelID)
            ->where('version', $productPriceQuery->version)
            ->get(['id_level', 'id_level_discount', 'discount_value', 'discount_price']);

        $desc_lvl1 = intval($queryLevel[0]->discount_price);
        $desc_lvl2 = intval($queryLevel[1]->discount_price); // nivel 2
        $desc_lvl3 = intval($queryLevel[2]->discount_price); // nivel 3
        $desc_lvl4 = intval($queryLevel[3]->discount_price); // nivel 4

        //return $queryLevel;

        if ($regulado == "REGULADO") {
            if ($quotaPrice  > $maxPrice) {
                $idLevel = "";
                $authlevel = 0;
                $done = 0;
                $msg = "El producto es regulado no puede aumentar el precio";
            } else {
                if ($quotaDesc == $prodPrice) {
                    $idLevel = $queryLevel[0]->id_level;
                    $authlevel = 1;
                    $done = 1;
                } else if ($quotaDesc > $prodPrice) {
                    $idLevel = $queryLevel[1]->id_level;
                    $authlevel = 2;
                    $done = 1;
                } else if ($quotaDesc < $prodPrice) {

                    if ($quotaDesc <= $desc_lvl2 && $quotaDesc > $desc_lvl3) {
                        $idLevel = $queryLevel[2]->id_level;
                        $authlevel = 3;
                        $done = 1;
                    } else if ($quotaDesc <= $desc_lvl3 && $quotaDesc >= $desc_lvl4) {
                        $idLevel = $queryLevel[3]->id_level;
                        $authlevel = 4;
                        $done = 1;
                    } else if ($quotaDesc == $desc_lvl4) {
                        $idLevel = $queryLevel[3]->id_level;
                        $authlevel = 4;
                        $done = 1;
                    } else if ($quotaDesc < $desc_lvl4) {
                        $idLevel = "";
                        $authlevel = "";
                        $done = 0;
                        $msg = "Ha superado el maximo de descuento permitido, por favor verifique";
                    } else {
                        $idLevel = $queryLevel[1]->id_level;
                        $authlevel = 2;
                        $done = 1;
                    }
                }
            }
        } else {
            if ($quotaDesc == $prodPrice) {
                $idLevel = $queryLevel[0]->id_level;
                $authlevel = 1;
                $done = 1;
            } else if ($quotaDesc > $prodPrice) {
                $idLevel = $queryLevel[1]->id_level;
                $authlevel = 2;
                $done = 1;
            } else if ($quotaDesc < $prodPrice) {
                if ($quotaDesc <= $desc_lvl2 && $quotaDesc > $desc_lvl3) {
                    $idLevel = $queryLevel[2]->id_level;
                    $authlevel = 3;
                    $done = 1;
                } else if ($quotaDesc <= $desc_lvl3 && $quotaDesc > $desc_lvl4) {
                    $idLevel = $queryLevel[3]->id_level;
                    $authlevel = 4;
                    $done = 1;
                } else if ($quotaDesc == $desc_lvl4) {
                    $idLevel = $queryLevel[3]->id_level;
                    $authlevel = 4;
                    $done = 1;
                } else if ($quotaDesc < $desc_lvl4) {
                    $idLevel = "";
                    $authlevel = "";
                    $done = 0;
                    $msg = "Ha superado el maximo de descuento permitido, por favor verifique";
                } else {
                    $idLevel = $queryLevel[1]->id_level;
                    $authlevel = 2;
                    $done = 1;
                }
            }
        }

        //return $authlevel;
        $response = [];
        $response["quotaDesc"]      = $quotaDesc;
        $response["prodPrice"]      = $prodPrice;
        $response["descPrecio"]     = $priceDisc;
        $response["idLevel"]        = $idLevel;
        $response["level"]          = $authlevel;
        $response["percent"]        = $percentValue;
        $response["text"]           = $textDescription;
        $response["permitido"]      = $done;
        $response["descSymbol"]     = $descSymbol;
        $response["regulado"]       = $regulado;
        $response["msg"]            = $msg;

        return ($response);
    }

    public function getQuotaTotal(Request $request)
    {
        $totalQuota = Quotation::where('id_quotation', $request->idQuota)->pluck('quota_value');
        return ($totalQuota);
    }

    public function getPayForm(Request $request)
    {
        $payform = PaymentTerms::where('id_payterms', $request->idPercent)->pluck('payterm_percent');
        return ($payform);
    }

    public function getEditProducts(Request $request)
    {
        //$quotationDetails = QuotationDetails::getProducts($request->idQuota);
        $productos = QuotationDetails::where('id_quotation', $request->idQuota)->join('nvn_products', 'nvn_quotations_details.id_product', '=', 'nvn_products.id_product')
            ->orderBy('nvn_products.prod_name', 'ASC')->select('nvn_quotations_details.*')->with('product', 'payterm')->get();

        return ($productos);
    }

    public function getProductsClient(Request $request)
    {
        $dateIni = new DateTime($request->dateIni);
        $dateIni->format('Y-m-d H:i:s');
        $dateEnd = new DateTime($request->dateEnd);
        $dateEnd->format('Y-m-d H:i:s');

        $clientProducts = [];
        $client     = Client::where('id_client', $request->idClient)->first();
        $products   = Product::orderby('prod_name', 'ASC')->with('prices')->get();

        if ($client->id_payterm == "") {
            $payMethod = 9;
        } else {
            $payMethod = $client->id_payterm;
        }
        $payTerm = PaymentTerms::where('id_payterms', $payMethod)->first();
        $dataArray = [];

        foreach ($products as $key => $prod) {
            $clientProduct  = QuotationDetails::where('id_client', $request->idClient)
            ->where('id_product', $prod->id_product)
            ->where(function($query){
                $query->where('is_valid', 1)
                ->orWhere('is_valid', 6);
            })
            ->first();
            $authlevel      = Product_AuthLevels::where('id_product', $prod->id_product)->where('id_level_discount', 1)->first();
            $authlevel3     = Product_AuthLevels::where('id_product', $prod->id_product)->where('id_level_discount', 3)->first();
            $price          = ProductxPrices::where('id_product', $prod->id_product)->where('prod_valid_date_ini', '<=', $dateIni)
                ->where('prod_valid_date_end', '>=', $dateIni)->where('active', 1)->first();

            if ($price) {
                $dataArray["id_product"]            = $prod->id_product;
                $dataArray["id_payterm"]            = $client->id_payterm;
                $dataArray["quantity"]              = 1;
                $dataArray["time_discount"]         = 0;
                $dataArray["pay_discount"]          = 0;
                $dataArray["commercial_discount"]   = 0;

                if ($clientProduct) {
                    $dataArray["preciocotActual"]       = $clientProduct->totalValue;
                } else {
                    $dataArray["preciocotActual"]       = 0;
                }

                $dataArray["preciolvl3"]            = $authlevel3->discount_price;

                if ($client->id_client_channel == 6) {
                    $dataArray["totalValue"]            = $price->v_institutional_price;
                    $dataArray["v_commercial_price"]    = $price->v_institutional_price;
                } else {
                    $dataArray["totalValue"]            = $price->v_commercial_price;
                    $dataArray["v_commercial_price"]    = $price->v_commercial_price;
                }

                $dataArray["prod_package_unit"]     = $prod->prod_package_unit;
                $dataArray["id_prod_auth_level"]    = $authlevel->id_level;
                $dataArray["prod_name"]             = $prod->prod_name;
                $dataArray["payterm_name"]          = $payTerm->payterm_name;
                $dataArray["levelAuth"]             = 1;

                array_push($clientProducts, $dataArray);
            }
        }

        return ($clientProducts);
    }

    public static function getCount($type)
    {
        $rol = auth()->user()->roles;
        $quotations = [];
        if ($rol[0]->slug == "cam" || $rol[0]->slug == "admin_venta") {
            $count = Quotation::where('created_by', auth()->user()->id)->whereIn('status_id', [3, 4, 5])->count();
            $clientes = Client::where('id_diab_contact', auth()->user()->id)->get();
            foreach ($clientes as $key => $cliente) {
                $quotationsQ = Quotation::where('id_client', $cliente->id_client)
                ->whereIn('status_id', [1,2,3,4,5])
                ->orderBy('id_quotation', 'DESC')
                ->with('cliente', 'users', 'channel')
                ->get();
                foreach ($quotationsQ as $key => $quota) {
                    array_push($quotations, $quota);
                }
            }
            $count = sizeof($quotations);
        } else {
            $count = Quotation::whereIn('status_id', [3,4,5])
            ->count();
        }

        return $count;
    }

    public static function getCountAllQuota()
    {

        $rol = auth()->user()->roles;
        $quotations = [];
        if ($rol[0]->slug == "cam") {
            $count = Quotation::where('created_by', auth()->user()->id)->count();
            $clientes = Client::where('id_diab_contact', auth()->user()->id)->get();
            foreach ($clientes as $key => $cliente) {
                $quotationsQ = Quotation::where('id_client', $cliente->id_client)->orderBy('id_quotation', 'DESC')->with('cliente', 'users', 'channel')->get();
                foreach ($quotationsQ as $key => $quota) {
                    array_push($quotations, $quota);
                }
            }
            $count = sizeof($quotations);
        } else {
            $count = Quotation::count();
        }

        return $count;
    }

    public static function getCountPre()
    {
        $count = Quotation::where('status_id', 1)->count();
        return $count;
    }

    public static function getCountAutorizaciones()
    {
        $quotations = Quotation::whereHas('approving', function ($query) {
            $query->where('user_id', Auth::user()->id)
            ->whereNull('answer');
        })
        ->whereIn('status_id', [3, 4, 5])
        ->count();

        return $quotations;
    }

    public function getAuthorizers(Request $request)
    {
        $level = $request->level;
        $users = User::where('is_authorizer', 1)->where('authlevel', $level)->get();
        return $users;
    }

    public function porcentaje($total, $parte, $redondear = 0)
    {
        return round($parte / $total * 100, $redondear);
    }

    public function createPDF($id)
    {
        $cotizacion     = Quotation::where('id_quotation', $id)->with('quotadetails', 'status', 'users', 'creator', 'cliente', 'approving')->first();
        $autorizador    = User::where('is_authorizer', 1)->where('id', $cotizacion->id_authorizer_user)->first();
        $productos      = QuotationDetails::where('id_quotation', $id)
                        ->where(function($query){
                            $query->where('is_valid', 1)
                            ->orWhere('is_valid', 6);
                        })
                        ->orderBy('id_quota_det', 'ASC')
                        ->with('product', 'payterm')
                        ->get();
        //dd($productos);
        $docFormat      = DocFormatType::where('format_name', 'LIKE', 'PDF Cotización')->first();
        $doc            = DocFormat::where('id_formattype', $docFormat->id_formattype)->first();
        $approving      = QuotationApprovers::where('quotation_id', $id)
                        ->whereNotNull('answer')
                        ->whereHas('approversUser', function ($query) {
                            $query->where('is_authorizer', '>', 0);
                        })->distinct('user_id')->get();
        if (!empty($autorizador)) {
            $autorizador = $autorizador->name;
        } else {
            $autorizador = "No necesita";
        }
        $forma_pagos = PaymentTerms::all();
        $location = Location::find($cotizacion->cliente->id_city);
        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $fecha = Carbon::parse(strtotime($cotizacion->quota_date_ini));
        $mes = $meses[($fecha->format('n')) - 1];
        $desde = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
        $fecha = Carbon::parse(strtotime($cotizacion->quota_date_end));
        $mes = $meses[($fecha->format('n')) - 1];
        $hasta = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
        if (!empty($cotizacion)) {
            $pdf = PDF::loadView('admin.quotations.template.pdf', compact('cotizacion', 'autorizador', 'productos', 'forma_pagos', 'location', 'desde', 'hasta', 'doc', 'approving'))
                ->setPaper('a4', 'landscape')
                ->save('downloads/' . 'NOVO-' . $cotizacion->quota_consecutive . '.pdf');
            return response()->download('downloads/' . 'NOVO-' . $cotizacion->quota_consecutive . '.pdf', 'NOVO-' . $cotizacion->quota_consecutive . '.pdf', [], 'inline');
        }
    }

    public function cancelQuota(Request $request, $id)
    {
        $quotation = Quotation::find($id);
        if ($quotation) {
            $quotation->is_authorized = 6;
            $quotation->status_id = 8;
            $quotation->comments = $request->comments;
            if ($quotation->update()) {
                $input = [
                    'is_valid'  =>   0,
                ];
                QuotationDetails::where('id_quotation', $id)->update($input);
                return redirect()->route('cotizaciones.index')->with('info', 'Cotización anulada satisfactoriamente');
            }
        }
    }

    public function getQuotationData(Request $request)
    {
        $idQuota = $request->idQuota;
        $quotation =  Quotation::where('id_quotation', $idQuota)->with('cliente', 'channel')->first();
        $time = strtotime($quotation->quota_date_ini);
        $dateIni = date('Y-m-d', $time);
        $time = strtotime($quotation->quota_date_end);
        $dateEnd = date('Y-m-d', $time);
        $quotation->quota_date_ini = $dateIni;
        $quotation->quota_date_end = $dateEnd;
        return $quotation;
    }

    public static function quotationSendEmail(Request $request)
    {
        $id         = $request->idQuota;
        $cotizacion = Quotation::where('id_quotation', $id)->with('quotadetails', 'status', 'users', 'creator', 'cliente')->first();
        $email      = $request->email;
        $from       = Auth::user()->email;
        $name       = Auth::user()->name;
        Mail::send('admin.quotations.email.quotaemail', compact('cotizacion'), function ($msj) use ($email, $from, $name, $cotizacion) {
            $msj->subject('Cotización '. $cotizacion->quota_consecutive .' enviada por ' . $name . ' desde Novo Nordisk CAM Tool');
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
            return redirect()->route('cotizaciones.index')->with('success', 'Cotización enviada exitosamente');
        }
    }
}

