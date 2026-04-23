<?php

namespace App\Http\Controllers;

use App\Channel_Types;
use App\Events\OrderNotificationsEvent;
use App\Product;
use App\Product_h;
use App\Product_Line;
use App\MeasureUnit;
use App\Excel;
use App\Imports\PricesImport;
use App\Notifications;
use App\PricesList;
use App\Product_AuthLevels;
use App\ProductxPrices;
use App\User;
use Illuminate\Http\Request;
use Caffeinated\Shinobi\Middleware;
use DateTime;
use Illuminate\Support\Facades\Validator;

// Mensajes Session
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Excel as ExcelExcel;

class ProductsPricesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){

        $isEditor = auth()->user()->hasPermissionTo('precios.index');
        if($isEditor){
            $pricelists     = PricesList::orderBy('id_pricelists','DESC')->get();
            $authorizers = User::where('is_authorizer',1)->where('authlevel','>=',3)->pluck('name','id');
            return view('admin.prices.index', compact('pricelists','authorizers'));

        }else{
            abort(403, 'Acción no autorizada.');
        }
    }



    public function indexOld()  // Esta version permitia ver los precios y modificarlos de forma individual por medio de un ligthbox // Fase 2
    {
        $isEditor = auth()->user()->hasPermissionTo('precios.index');
        if($isEditor){
        $authorizers = User::where('is_authorizer',1)->pluck('name','id');
        $productos = ProductxPrices::orderBy('id_product','ASC')->with('product')->get();
        //dd($productos[0]->prices);
        return view('admin.products.index_price', compact('productos','vigencia','authorizers'));
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $isEditor = auth()->user()->hasPermissionTo('precios.index');
        if($isEditor){
            $producto = Product::find($request->id_product);

            if($request->comments == ""){
                $comments = "Sin comentarios";
            }
            //dd($producto);
            // Tabla histórico
            $product_h = new Product_h;
            $product_h->id_product_h = $producto->id_product;
            $product_h->modification_type = $request->modification_type;
            $product_h->comments = $comments;
            $product_h->v_institutional_price = $producto->v_institutional_price;
            $product_h->v_commercial_price = $producto->v_commercial_price;
            $product_h->prod_valid_date_ini = $producto->prod_valid_date_ini;
            $product_h->prod_valid_date_end = $producto->prod_valid_date_end;
            $product_h->save();

            $producto->v_institutional_price = $request->v_institutional_price;
            $producto->v_commercial_price = $request->v_commercial_price;
            $producto->prod_valid_date_ini = $request->prod_valid_date_ini;
            $producto->prod_valid_date_end = $request->prod_valid_date_end;
            $producto->update();

            if ($producto->save()) {
                return redirect()->route('prices.index')->with('info','Producto actualizado exitosamente');
            }else{
                return redirect()->route('prices.index')->with('error','Existió un error en la modificación');
            }
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }


      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('precios.index');
        if($isEditor){
            $pricelist      = PricesList::where('id_pricelists',$id)->first();
            $productos      = ProductxPrices::where('id_pricelists',$id)->orderBy('id_productxprices','ASC')->get();
            $autorizador    = User::where('is_authorizer',1)->where('id', $pricelist->id_authorizer_user)->first();
            $pricel = [];
            $pricelistcomercial     = [];
            $pricelistinstitucional = [];

            foreach ($productos as $key => $producto) {
                $authlevels = Product_AuthLevels::where('id_pricelists',$id)->where('id_product',$producto->id_product)->where('id_dist_channel',5)->orderBy('id_level_discount','ASC')->get();
                array_push($pricelistcomercial,$authlevels);
            }

            $pricel2 = [];
            foreach ($productos as $key => $producto) {
                $authlevels = Product_AuthLevels::where('id_pricelists',$id)->where('id_product',$producto->id_product)->where('id_dist_channel',6)->orderBy('id_level_discount','ASC')->get();
                array_push($pricelistinstitucional,$authlevels);
            }

            //dd($pricelistinstitucional[0]);


            return view('admin.prices.show', compact( 'pricelist','autorizador','productos','pricelistcomercial','pricelistinstitucional'));
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('precios.index');
        if($isEditor){
            $producto = Product::find($id);
            return $producto;
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       dd($request);
    }

    public function updatePrice(Request $request)
    {
        $isEditor = auth()->user()->hasPermissionTo('precios.index');
        if($isEditor){
            $price = ProductxPrices::where('id_productxprices',$request->id_productxprices)->first();
            $producto = Product::find($request->id_product);

            if($request->comments == ""){
                $comments = "Sin comentarios";
            }else{
                $comments = $request->comments;
            }

            $product_h = new Product_h;
            $product_h->id_product_h            = $request->id_product;
            $product_h->modification_type       = $request->modification_type;
            $product_h->comments                = $comments;
            $product_h->v_institutional_price   = $producto->v_institutional_price;
            $product_h->v_commercial_price      = $producto->v_commercial_price;
            $product_h->prod_valid_date_ini     = $producto->prod_valid_date_ini;
            $product_h->prod_valid_date_end     = $producto->prod_valid_date_end;

            if($product_h->save()){
                $price->v_institutional_price   = $request->v_institutional_price;
                $price->v_commercial_price      = $request->v_commercial_price;
                $price->prod_valid_date_ini     = $request->prod_valid_date_ini;
                $price->prod_valid_date_end     = $request->prod_valid_date_end;
                $price->active                  = 0;

                if ($price->update()) {
                    return redirect()->route('prices.index')->with('info','Producto actualizado exitosamente');
                }else{
                    return redirect()->route('prices.index')->with('error','Existio un error en la modificación');
                }
            }



        }else{
            abort(403, 'Acción no autorizada.');
        }
    }


    public function getProduct(Request $request)
    {
        //dd($request);
        $producto = ProductxPrices::where('id_product',$request->idProduct)->with('product')->first();
        /*$inst = "8";
        $producto->appends(['referencia' => $inst]);*/
        //$producto->appends(['institucional' => $inst]);
        return $producto;
    }

    public function getPriceData(Request $request)
    {
        $arraydata  = [];
        $idPrices  = $request->idPrices;
        $pricelist = ProductxPrices::where('id_pricelists', $idPrices)->first();
        $listname  = PricesList::where('id_pricelists', $idPrices)->first();
        $time      = strtotime($pricelist->prod_valid_date_ini);
        $dateIni   = date('Y-m-d', $time);
        $time      = strtotime($pricelist->prod_valid_date_end);
        $dateEnd   = date('Y-m-d', $time);
        $pricelist->prod_valid_date_ini = $dateIni;
        $pricelist->prod_valid_date_end = $dateEnd;
        array_push($arraydata, $pricelist);
        array_push($arraydata, $listname);
        return $arraydata;
    }

    public function updatedate(Request $request)
    {
        // dd($request->idPrices);
        $isEditor = auth()->user()->hasPermissionTo('precios.index');
        if ($isEditor) {
            $id = $request->idPrices;
            $priceList = ProductxPrices::where('id_pricelists', $id)->get();
            foreach ($priceList as $key => $id) {
                $idProductxPrice = $id->id_productxprices;
                $productPrice = ProductxPrices::find($idProductxPrice);
                $productPrice->prod_valid_date_ini      = $request->prod_valid_date_ini;
                $productPrice->prod_valid_date_end      = $request->prod_valid_date_end;
                $productPrice->update();
            }
            return redirect()->route('prices.index')->with('info', 'Fecha actualizada exitosamente');
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function pricesMasive(Request $request)
    {
        $hasFile = $request->hasFile('doc');

        if($hasFile){
            //Excel::import(new PricesImport, request()->file('doc'));
            $prices = (new PricesImport)->toArray(request()->file('doc'));
            $counter = 0;
            foreach ($prices[0] as $key => $price) {
                for ($i=0; $i < sizeof($price) ; $i++) {
                    $tempArray["codigo_sap"]            = $price["codigo_sap"];
                    $tempArray["producto"]              = $price["producto"];
                    $tempArray["canal"]                 = $price["canal"];
                    if($price["wsp"] != "N/A"){
                    $tempArray["wsp"]                   = round($price["wsp"],0,PHP_ROUND_HALF_UP);
                    }else{
                    $tempArray["wsp"]                   = 0;
                    }
                    $tempArray["wpp"]                   = round($price["wpp"],0,PHP_ROUND_HALF_UP);
                    $tempArray["descuento_nivel_1"]     = round($price["descuento_comercial_volumen_nivel_1"] * 100,0);
                    $tempArray["precio_nivel_1"]        = 0;
                    $tempArray["descuento_nivel_2"]     = round($price["descuento_comercial_volumen_o_financiero_nivel_2"] * 100,0);
                    $tempArray["precio_nivel_2"]        = round($price["precio_minimo_nivel_2"],0,PHP_ROUND_HALF_UP);
                    $tempArray["descuento_nivel_3"]     = round($price["descuento_comercial_volumen_o_financiero_nivel_3"] * 100,0);
                    $tempArray["precio_nivel_3"]        = round($price["precio_minimo_nivel_3"],0,PHP_ROUND_HALF_UP);
                    $tempArray["descuento_nivel_4"]     = round($price["descuento_comercial_volumen_o_financiero_nivel_4"] * 100,0);
                    $tempArray["precio_nivel_4"]        = round($price["precio_minimo_nivel_4"],0,PHP_ROUND_HALF_UP);
                    $tempArray["vigencia_desde"]        = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($price["vigencia_desde"]);
                    $tempArray["vigencia_hasta"]        = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($price["vigencia_hasta"]);
                    $tempArray["comments"]              = $price["comentarios"];
                }

                $importPrices[$key] = $tempArray;
            }

            //dd($importPrices);

            // verifica que los productos existan en la base de datos
            foreach($importPrices as $key => $importPrice){

                $sapcode = $importPrice['codigo_sap'];

                $product = Product::where('prod_sap_code', $sapcode)
                ->orWhereHas('sapCodes', function ($query) use ($sapcode) {
                    $query->where('sap_code', $sapcode);
                })->first();

                if(empty($product)){
                    alert()->error('El producto '.$importPrice["producto"].' de la fila '.($key + 2).' no existe en la base de productos, NO SE CARGARON DATOS');
                    return redirect()->back();
                }

                if($importPrice["vigencia_hasta"] <= $importPrice["vigencia_desde"]){
                    alert()->error('En el producto '.$importPrice["producto"].' de la fila '.($key + 2).' la fecha de fin de vigencia es mayor que la de inicio , NO SE CARGARON DATOS');
                    return redirect()->back();
                }

                $dateIni = $importPrice["vigencia_desde"];
                $dateEnd = $importPrice["vigencia_hasta"];

                // Verifica que la fecha de inicio de vigencia no sea igual a una de las listas previas
                $verifiIni =  ProductxPrices::where('id_product',$product->id_product)->where('prod_valid_date_ini' ,'=' ,$dateIni)->where('active',1)->get();
                if(sizeof($verifiIni) > 0){
                    alert()->error('En el producto '.$importPrice["producto"].' de la fila '.($key + 2).' la fecha de inicio de vigencia es igual a una lista activa , NO SE CARGARON DATOS');
                    return redirect()->back();
                }


            }

            $queryv = PricesList::orderBy('id_pricelists','desc')->first();
            if(!empty($queryv)){
                $version = $queryv->list_version + 1;
            }else{
                $version = 1;
            }

            $newList = new PricesList();
            $newList->list_name             = $request->list_name;
            $newList->id_authorizer_user    = $request->id_authorizer;
            $newList->list_version          = $version;

            if($newList->save()){

            }else{
                alert()->error('Existio un error al guardar por favor inténtelo nuevamente');
                return redirect()->back();
            }


            // Importa los precios
            foreach($importPrices as $key => $importPrice){

                $sapcode = $importPrice['codigo_sap'];

                $product = Product::where('prod_sap_code', $sapcode)
                ->orWhereHas('sapCodes', function ($query) use ($sapcode) {
                    $query->where('sap_code', $sapcode);
                })->first();

                // Import de precios
                if(empty($product)){
                    alert()->error('El producto '.$importPrice["producto"].' de la fila '.($key + 2).' no existe en la base de productos, NO SE CARGARON DATOS');
                    return redirect()->back();
                }else if(!empty($product)){

                    $price = ProductxPrices::where('prod_sap_code', $importPrice['codigo_sap'])->where('prod_valid_date_ini', $importPrice['vigencia_desde'])->where('version',$version)->first();
                    if (!empty($price)) {

                        $price->id_product      =   $product->id_product;
                        $price->prod_sap_code   =   $product->prod_sap_code;
                        if($importPrice['canal'] == "INSTITUCIONAL"){
                            $price->v_institutional_price =   $importPrice['wpp'];
                        }else{
                            $price->v_commercial_price =   $importPrice['wpp'];
                            //dd($importPrice['wpp']);
                        }

                        if($importPrice['wsp'] > 0){
                            $price->prod_increment_max = $importPrice['wsp'];
                        }else{
                            $price->prod_increment_max = "N/A";
                        }

                        $price->prod_valid_date_ini = $importPrice['vigencia_desde'];
                        $price->prod_valid_date_end = $importPrice['vigencia_hasta'];
                        $price->comments            = $importPrice['comments'];
                        $price->version             = $version;

                        if($price->update()){
                            $counter++;
                        }

                    } else {
                        $price = new ProductxPrices();
                        $price->id_pricelists   =   $newList->id_pricelists;
                        $price->id_product      =   $product->id_product;
                        $price->prod_sap_code   =   $product->prod_sap_code;

                        if($importPrice['canal'] == "INSTITUCIONAL"){
                            $price->v_institutional_price =   $importPrice['wpp'];
                        }else{
                            $price->v_commercial_price =   $importPrice['wpp'];
                            //dd($importPrice['wpp']);
                        }

                        if($importPrice['wsp'] > 0){
                            $price->prod_increment_max = $importPrice['wsp'];
                        }else{
                            $price->prod_increment_max = "N/A";
                        }

                        $price->prod_valid_date_ini =  $importPrice['vigencia_desde'];
                        $price->prod_valid_date_end =  $importPrice['vigencia_hasta'];
                        $price->comments            = $importPrice['comments'];
                        $price->version             =  $version;

                        if($price->save()){
                            $counter++;
                        }
                    }


                }

                // Import de niveles de autorizacion


                $dist_channel = Channel_Types::where('channel_name','~*',$importPrice['canal'])->first(); // POSTGRES
                //$dist_channel = Channel_Types::where('channel_name','LIKE',$importPrice['canal'])->first(); // MYSQL
                $id_channel = $dist_channel->id_channel;

                for ($i=0; $i < 4 ; $i++) {
                    $level = $i+1;
                    // dd($importPrice['descuento_nivel_'.$level]);
                    $instLevel = new Product_AuthLevels();
                    $instLevel->id_product          = $product->id_product;
                    $instLevel->id_dist_channel     = $id_channel;
                    $instLevel->id_level_discount   = $level;
                    $instLevel->discount_value    = $importPrice['descuento_nivel_'.$level];
                    $instLevel->discount_price      = $importPrice['precio_nivel_'.$level];
                    $instLevel->id_pricelists       = $newList->id_pricelists;
                    $instLevel->version             = $version;
                    $instLevel->save();
                }

            }


            $users_notified = User::where('is_authorizer',1)->where('authlevel',3)->get();

            $notiUsers = [];
            foreach($users_notified as $user){
                $notification = Notifications::create([
                    'destiny_id'    => $user->id,
                    'sender_id'     => Auth()->user()->id,
                    'type'          => 'Actualización de cliente',
                    'data'          => 'Se ha agregado la lista de precios '. $instLevel->version,
                    'url'           => "/products/",
                    'readed'        => 0,
                ]);
                array_push($notiUsers, $user->id);
            }

            $not['url']             = 'autorizarlist/' . $newList->id_pricelist;
            $not['description']     = 'Se ha agregado la lista de precios '. $instLevel->version;
            $not['userId']          = $notiUsers;
            event(new OrderNotificationsEvent($not));

            toastr()->success('!Registro guardado exitosamente!');
            return redirect()->back();

        }else{
            alert()->error('Debe adjuntar un archivo valido');
            return redirect()->back();
        }

        toastr()->success('!Registro guardado exitosamente!');
        return redirect()->back();
    }

}
