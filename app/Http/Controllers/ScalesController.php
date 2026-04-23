<?php

namespace App\Http\Controllers;

use App\Channel_Types;
use App\ClientxProductScale;
use App\NegotiationDetails;
use App\Product;
use App\QuotationDetails;
use App\Scales;
use App\ScalesLevels;
use Auth0\SDK\API\Management\Clients;
use Illuminate\Http\Request;

class ScalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('scales.index');
        if($isEditor){
            $productos  = Product::orderBy('id_product','ASC')->pluck('prod_name','id_product');
            $escalas    = Scales::orderBy('id_product','DESC')->get();
            $channels   = Channel_Types::orderBy('channel_name','ASC')->pluck('channel_name','id_channel');
            return view('admin.scales.index', compact('escalas','productos','channels'));
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }

    public function getScales(Request $request){
        $escalatemp = [];
        $escalasRes = [];
        $idProduct = $request->idProduct;
        $escalas = Scales::where('id_product', $idProduct)->orderBy('scale_number')->with('scalelvl')->get();

        //return $escalas;
        foreach ($escalas as $key => $escala) {

            $escalatemp['id_product']   = $escala->id_product;
            $escalatemp['id_channel']   = $escala->id_channel;
            $escalatemp['id_scale']     = $escala->id_scale;
            $escalatemp['scale_number'] = $escala->scale_number;

            $escalalvl = ScalesLevels::where('id_scale',$escala->id_scale)->with('measureUnit')->get();
            $escalatemp['scalelvl']     = $escalalvl;
            $unit = Product::where('id_product', $idProduct)->with('measureUnit')->first();
            $escalatemp['prod_unit_id']     = $unit->id_measure_unit;
            $escalatemp['prod_unit_text']     = $unit->measureUnit->unit_name;

            array_push($escalasRes, $escalatemp);

        }


        return $escalasRes;
    }

    /**
     * Muestra la unidad minima del producto en funcion del tipo de medida (UI,UNIDAD,MG)
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductUnit(Request $request)
    {
        $idProduct = $request->idProduct;
        $query = Product::where('id_product',$idProduct)->with('measureUnit')->first();
        return $query->measureUnit;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /*$validateScale = Scales::where('id_product',$request->idProduct)->where('id_channel',$request->idChannel)->get();

        if($validateScale->isNotEmpty()){
            return "exist";
        }*/

        $scalesArray = $request->scales;
        $idProduct = $request->idProduct;
        $scale = new Scales();
        $scale->id_product      = $idProduct;
        $scale->scale_number    = strtoupper($request->scaleName);
        //$scale->id_channel      = $request->idChannel;
        if ($scale->save()) {
            for ($i=0; $i < sizeof($scalesArray); $i++) {
                $scalelvl = new ScalesLevels();
                $scalelvl->scale()->associate($scale->id_scale);
                $scalelvl->scale_discount  = $scalesArray[$i]['porcentaje'];
                $scalelvl->scale_min       = $scalesArray[$i]['piso'];
                $scalelvl->scale_max       = $scalesArray[$i]['techo'];
                $scalelvl->id_measure_unit = $scalesArray[$i]['units'];
                $scalelvl->save();
            }
        } else {
            return "error";
        }
        $escalas = Scales::where('id_product', $idProduct)->orderBy('scale_number')->with('scalelvl')->get();
        return $escalas;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editScales(Request $request)
    {
        $idScale = $request->idProduct;
        $escalas = Scales::where('id_scale', $idScale)->with('scalelvl')->first();
        return $escalas;
    }


    /** Update scale desde el modal */

    public function updateScales(Request $request)
    {
       // dd($request);
        $scale = ClientxProductScale::where('id_client',$request->idClient)->where('id_product',$request->idProduct)->first();
        if($scale){
            $scale->id_scale = $request->idNewScale;
            if($scale->update()){
                return "OK";
            }else{
                return "Error";
            }
        }else{
            $newScale = new ClientxProductScale();
            $newScale->id_client    = $request->idClient;
            $newScale->id_product   = $request->idProduct;
            $newScale->id_scale     = $request->idNewScale;
            if($newScale->save()){
                return "OK";
            }else{
                return "Error";
            }
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
        $scalesArray    = $request->scales;
        $idProduct      = $request->idProduct;
        //$idChannel      = $request->idChannel;

        $scale = Scales::where('id_scale',$request->idScale)->first();
        $scale->id_product      = $idProduct;
        $scale->scale_number    = strtoupper($request->scaleName);
        //$scale->id_channel      = $idChannel;
        if ($scale->save()) {
            if($request->idScale != ""){
                ScalesLevels::where('id_scale',$request->idScale)->delete();
            }
            //return $scale->id_scale;
            $idScale = $scale->id_scale;
            for ($i=0; $i < sizeof($scalesArray); $i++) {
                $scalelvl = new ScalesLevels();
                $scalelvl->scale()->associate($scale->id_scale);
                $scalelvl->scale_discount  = $scalesArray[$i]['porcentaje'];
                $scalelvl->scale_min       = $scalesArray[$i]['piso'];
                $scalelvl->scale_max       = $scalesArray[$i]['techo'];
                $scalelvl->id_measure_unit = $scalesArray[$i]['units'];
                $scalelvl->save();
            }

            // Actualiza las escalas directamente en el cliente de forma automática.

            /*$clients = QuotationDetails::where('id_product',$idProduct)
            ->whereHas('quotation', function ($query){
                $query->where('is_authorized',4)
                ->orWhere('status_id',6);
            })
            ->whereHas('client', function ($query) use ($idChannel){
                $query->where('id_client_channel',$idChannel);
            })->groupBy('id_client')->get('id_client');

            foreach ($clients as $key => $client) {
                $scaleClient = ClientxProductScale::where('id_product',$idProduct)
                ->where('id_client',$client->id_client)
                ->first();
                if($scaleClient){
                    $scaleClient->id_scale = $idScale;
                    $scaleClient->update();
                }else{
                    ClientxProductScale::create([
                        'id_client'  => $client->id_client,
                        'id_product' => $idProduct,
                        'id_scale'   => $idScale,
                    ]);
                }
            }*/

        } else {
            return "error";
        }
        $escalas = Scales::where('id_product', $idProduct)->orderBy('scale_number')->with('scalelvl')->get();
        return $escalas;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $idScale        = $request->idScale;
        $idProduct      = $request->idProduct;
        $verifyScale    = NegotiationDetails::where('id_scale',$idScale)->get();
        if($verifyScale->isNotEmpty()){
            $escalas = Scales::where('id_product', $idProduct)->orderBy('scale_number')->with('scalelvl')->get();
            return ['exist',$escalas];
        }else{
            $scale = Scales::where('id_scale',$idScale)->first();
            if ($scale->delete()) {
                $escalas = Scales::where('id_product', $idProduct)->orderBy('scale_number')->with('scalelvl')->get();
                return $escalas;
            } else {
                return "error";
            }

        }




    }
}
