<?php

namespace App\Http\Controllers;

use App\Product;
use App\Product_h;
use App\Product_Line;
use App\MeasureUnit;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Caffeinated\Shinobi\Middleware;
use Illuminate\Support\Facades\Validator;
use Alert;
use App\AditionalUses;
use App\Brands;
use App\Events\OrderNotificationsEvent;
use App\Exports\ProductsExport;
use App\Notifications;
use App\Product_AuthLevels;
use App\ProductxPrices;
use App\Product_Sap_Codes;
use App\User;
use Carbon\Carbon;
// Mensajes Session
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('products.index');
        if ($isEditor) {
            $productos = Product::orderBy('id_product', 'ASC')->with('measureUnit', 'productLine', 'aditionalUse')->get();
            //dd($productos);
            return view('admin.products.index', compact('productos'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function reportProduct()
    {
        $fecha = Carbon::now();
        $export = new ProductsExport();
        return Excel::download($export, 'Reporte_Novo_Productos_' . $fecha . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEditor = auth()->user()->hasPermissionTo('products.index');
        if ($isEditor) {
            $product_lines = Product_Line::orderBy('line_name', 'ASC')->pluck('line_name', 'id_line');
            $measure_unit = MeasureUnit::orderBy('unit_name', 'ASC')->pluck('unit_name', 'id_unit');
            $aditional_use = AditionalUses::orderBy('use_name', 'ASC')->pluck('use_name', 'id_use');
            $brands = Brands::orderBy('brand_name', 'ASC')->pluck('brand_name', 'id_brand');

            return view('admin.products.create', compact('product_lines', 'measure_unit', 'aditional_use', 'brands'))->with('success', 'Producto guardado exitosamente');
        } else {
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
        //$product = Product::create($request->all());
         //dd($request);
        $isEditor = auth()->user()->hasPermissionTo('products.index');
        if ($isEditor) {
            // Validación de datos del formulario
            $validation = Validator::make($request->all(), [
                'prod_name'             => 'required|max:191',
                'prod_commercial_name'  => 'required|max:200',
                'prod_sap_code'         => 'required|max:191',
                'prod_commercial_unit'  => 'required',
                'id_prod_line'          => 'required',
                'prod_valid_date_ini'   => 'required',
                'prod_valid_date_end'   => 'required',
                'prod_invima_reg'       => 'required',
                'prod_package'          => 'required',
                'prod_package_unit'     => 'required',
                'id_measure_unit'       => 'required',
                'id_brand'              => 'required',
                'created_by'            => 'required',
            ]);

            if ($validation->fails()) {
                return redirect('products/create')
                    ->withErrors($validation)
                    ->withInput();
            }

            //Product::create($request->all());
            $product = new Product();
            $product->prod_name             = $request->prod_name;
            $product->prod_generic_name     = $request->prod_generic_name;
            $product->prod_commercial_name  = $request->prod_commercial_name;
            // $product->prod_sap_code         = $request->prod_sap_code;
            $product->prod_commercial_unit  = $request->prod_commercial_unit;
            $product->id_prod_line          = $request->id_prod_line;
            $product->prod_valid_date_ini   = $request->prod_valid_date_ini;
            $product->prod_valid_date_end   = $request->prod_valid_date_end;
            $product->prod_invima_reg       = $request->prod_invima_reg;
            $product->prod_package          = $request->prod_package;
            $product->prod_package_unit     = $request->prod_package_unit;
            $product->id_measure_unit       = $request->id_measure_unit;
            if ($request->v_commercial_price != "") {
                $product->v_commercial_price    = $request->v_commercial_price;
            }
            if ($request->v_institutional_price != "") {
                $product->v_institutional_price = $request->v_institutional_price;
            }
            $product->is_prod_regulated     = $request->is_prod_regulated;
            $product->created_by            = $request->created_by;
            $product->prod_increment_max    = $request->prod_increment_max;
            $product->prod_cum              = $request->prod_cum;
            $product->prod_cod_IUM          = $request->prod_cod_IUM;
            $product->prod_cod_ATC          = $request->prod_cod_ATC;
            $product->prod_cod_EAN          = $request->prod_cod_EAN;
            $product->prod_concentration    = $request->prod_concentration;
            $product->id_brand              = $request->id_brand;
            $product->arp_divide            = $request->arp_divide;
            // dd($product->id_product);
            if ($product->save()) {
                if (isset($request->prod_sap_code)) {
                    $splits = explode(',', $request->prod_sap_code);
                    foreach ($splits as $split) {
                        $prod_sap_code              =  new Product_Sap_Codes();
                        $prod_sap_code->id_product  = $product->id_product;
                        $prod_sap_code->sap_code    = $split;
                        $prod_sap_code->save();
                    }
                }
                $users_notified = User::whereHas(
                    'roles',
                    function ($q) {
                        $q->where('slug', 'admin_venta');
                    }
                )->get();
                $url = URL::to("/");
                $notiUsers = [];
                foreach ($users_notified as $user) {
                    $notification = Notifications::create([
                        'destiny_id'    => $user->id,
                        'sender_id'     => Auth()->user()->id,
                        'type'          => 'Creación de nuevo producto',
                        'data'          => 'Se ha creado un nuevo producto ' . $product->prod_name,
                        'url'           => "products/" . $product->id_product,
                        'readed'        => 0,
                    ]);
                    array_push($notiUsers, $user->id);
                }

                $not['description']    = 'Se ha creado un nuevo producto ' . $product->prod_name;
                $not['url']            = $url . "products/" . $product->id_product;
                $not['userId']         = $notiUsers;
                event(new OrderNotificationsEvent($not));

                return redirect()->route('products.index')->with('success', 'Producto guardado exitosamente');
            } else {
                return redirect()->back()->with('error', 'Existio un error al guardar el producto');
            }
        } else {
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
        $isEditor = auth()->user()->hasPermissionTo('products.index');
        if ($isEditor) {
            $product                = Product::where('id_product', $id)->with('brand')->first();
            $product_prices         = ProductxPrices::where('id_product', $id)->where('active', 1)->with('list')->get();
            $product_lines          = Product_Line::where('id_line', $product->id_prod_line)->first(['line_name']);
            $measure_unit           = MeasureUnit::where('id_unit', $product->id_measure_unit)->first(['unit_name']);
            $productos              = ProductxPrices::where('id_product', $id)->orderBy('id_productxprices', 'ASC')->get();
            $product_sap_codes      = Product_Sap_Codes::where('id_product', $id)->pluck('sap_code');
            $result                 = [];
            array_push($result, $product_sap_codes);
            $a             = implode(',', $result);
            $sap           = str_replace(array('\'', '"', '[', ']'), '', $a);

            $pricelistcomercial     = [];
            $pricelistinstitucional = [];

            foreach ($productos as $key => $producto) {
                $authlevels         = Product_AuthLevels::where('id_pricelists', $id)->where('id_product', $producto->id_product)->where('id_dist_channel', 5)->orderBy('id_level_discount', 'ASC')->get();
                array_push($pricelistcomercial, $authlevels);
            }

            $pricel2                = [];
            foreach ($productos as $key => $producto) {
                $authlevels         = Product_AuthLevels::where('id_pricelists', $id)->where('id_product', $producto->id_product)->where('id_dist_channel', 6)->orderBy('id_level_discount', 'ASC')->get();
                array_push($pricelistinstitucional, $authlevels);
            }
            return view('admin.products.show', compact('product', 'product_lines', 'measure_unit', 'product_prices', 'productos', 'pricelistcomercial', 'pricelistinstitucional', 'sap'));
        } else {
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
        $isEditor = auth()->user()->hasPermissionTo('products.index');
        if ($isEditor) {
            $producto               = Product::find($id);
            $product_prices         = ProductxPrices::where('id_product', $id)->get();
            $product_lines          = Product_Line::orderBy('line_name', 'ASC')->pluck('line_name', 'id_line');
            $measure_unit           = MeasureUnit::orderBy('unit_name', 'ASC')->pluck('unit_name', 'id_unit');
            $aditional_use          = AditionalUses::orderBy('use_name', 'ASC')->pluck('use_name', 'id_use');
            $brands                 = Brands::orderBy('brand_name', 'ASC')->pluck('brand_name', 'id_brand');
            $product_sap_codes      = Product_Sap_Codes::where('id_product', $id)->pluck('sap_code');
            $sap                    = '';
            foreach ($product_sap_codes as $key => $code) {
                $sap  = $sap . ',' . $code;
            }

            return view('admin.products.edit', compact('producto', 'product_prices', 'product_lines', 'measure_unit', 'aditional_use', 'brands', 'sap'));
        } else {
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
    public function update(Product $product)
    {
        //dd(request());
        $isEditor = auth()->user()->hasPermissionTo('products.edit');
        if ($isEditor) {
            $regulated = request()->is_prod_regulated;
            //dd($regulated);
            $obesidad = request()->prod_obesidad;
            $insumo = request()->prod_insumo;
            if ($obesidad == null) {
                $obesidad = 0;
            }

            if ($insumo == null) {
                $insumo = 0;
            }

            $product->aditional_use         = request()->aditional_use;
            $product->prod_generic_name     = request()->prod_generic_name;
            // $product->prod_sap_code         = request()->prod_sap_code;
            $product->prod_commercial_unit  = request()->prod_commercial_unit;
            $product->id_prod_line          = request()->id_prod_line;
            $product->prod_cum              = request()->prod_cum;
            $product->id_measure_unit       = request()->id_measure_unit;
            $product->prod_package          = request()->prod_package;
            $product->prod_package_unit     = request()->prod_package_unit;
            $product->prod_commercial_name  = request()->prod_commercial_name;
            $product->is_prod_regulated     = $regulated;
            $product->prod_obesidad         = $obesidad;
            $product->prod_insumo           = $insumo;
            $product->prod_cod_IUM          = request()->prod_cod_IUM;
            $product->prod_cod_ATC          = request()->prod_cod_ATC;
            $product->prod_cod_EAN          = request()->prod_cod_EAN;
            $product->arp_divide            = request()->arp_divide;
            $product->prod_valid_date_ini   = request()->prod_valid_date_start;
            $product->prod_valid_date_end   = request()->prod_valid_date_expire;

            if (request()->id_brand != "") {
                $product->id_brand          = request()->id_brand;
            }

            $com_prices = request()->v_commercial_price;
            $ins_prices = request()->v_institutional_price;
            $max_prices = request()->prod_increment_max;
            $ini_dates  = request()->prod_valid_date_ini;
            $end_dates  = request()->prod_valid_date_end;
            if ($product->update()) {
                // dd($product->id_product);
                if (isset(request()->prod_sap_code)) {
                    Product_Sap_Codes::where('id_product', $product->id_product)->delete();
                    $splits = explode(',', request()->prod_sap_code);
                    foreach ($splits as $key => $split) {
                        $prod_sap_code =  new Product_Sap_Codes();
                        $prod_sap_code->id_product = $product->id_product;
                        $prod_sap_code->sap_code   = $split;
                        $prod_sap_code->save();
                    }
                }
            }

            $users_notified = User::whereHas(
                'roles',
                function ($q) {
                    $q->where('slug', 'admin_venta');
                }
            )->get();
            $url = URL::to("/");
            $notiUsers = [];
            foreach ($users_notified as $user) {
                $notification = Notifications::create([
                    'destiny_id'    => $user->id,
                    'sender_id'     => Auth()->user()->id,
                    'type'          => 'Modificación de producto',
                    'data'          => 'Se ha modificado el producto ' . $product->prod_name,
                    'url'           => "products/" . $product->id_product,
                    'readed'        => 0,
                ]);
                array_push($notiUsers, $user->id);
            }

            $not['description']    = 'Se ha modificado el producto ' . $product->prod_name;
            $not['url']            = $url . "products/" . $product->id_product;
            $not['userId']         = $notiUsers;
            event(new OrderNotificationsEvent($not));

            return redirect()->route('products.edit', $product->id_product)->with('info', 'Producto actualizado exitosamente');
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('products.index');
        if ($isEditor) {
            // Eliminado del producto
            /*$product = Product::where('id_product', $id)->first();
            $users_notified = User::whereHas(
                'roles',
                function ($q) {
                    $q->where('slug', 'admin_venta');
                }
            )->get();

            $url = URL::to("/");
            $notiUsers = [];
            foreach ($users_notified as $user) {
                $notification = Notifications::create([
                    'destiny_id'    => $user->id,
                    'sender_id'     => Auth()->user()->id,
                    'type'          => 'Modificación de producto',
                    'data'          => 'Se ha modificado el producto ' . $product->prod_name,
                    'url'           => "/products/",
                    'readed'        => 0,
                ]);
                array_push($notiUsers, $user->id);
            }

            $not['description']    = 'Se ha modificado el producto ' . $product->prod_name;
            $not['url']            = $url . "products";
            $not['userId']         = $notiUsers;
            event(new OrderNotificationsEvent($not));*/

            $product = Product::find($id);
            if ($product) {
                $product->delete();
            }

            return redirect()->route('products.index')->with('info', 'Producto eliminado satisfactoriamente');
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function productsMasive(Request $request)
    {
        $hasFile = $request->hasFile('doc');
        if ($hasFile) {
            Excel::import(new ProductsImport, request()->file('doc'));
        } else {
            alert()->error('Debe adjuntar un archivo valido');
            return redirect()->back();
        }
        toastr()->success('!Registro guardado exitosamente!');
        return redirect()->back();
    }
    public function passedInfo(Request $request)
    {
        $isEditor = auth()->user()->hasPermissionTo('products.index');
        if ($isEditor) {
            $products  = Product::all();
            foreach ($products as $product) {
                // dd($product->id_product);
                $prod_sap_code              =  new Product_Sap_Codes();
                $prod_sap_code->id_product  = $product->id_product;
                $prod_sap_code->sap_code    = $product->prod_sap_code;
                $prod_sap_code->save();
            }
        } else {
            abort(403, 'Acción no autorizada.');
        }

        toastr()->success('!Registro guardado exitosamente!');
        return redirect()->back();
    }
}
