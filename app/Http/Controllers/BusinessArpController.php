<?php

namespace App\Http\Controllers;

use App\Brands;
use App\BusinessArp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BusinessArpController extends Controller
{
    public function store(Request $request)
    {
        $id        = $request->arp_id;
        $brands    = $request->brands;

        for ($i=0; $i < sizeof($brands); $i++){
            $pbc                    = new BusinessArp();
            $pbc->arp_id            = $id;
            $pbc->brand_id          = $request->brands[$i];
            $pbc->pbc               = $request->pbc[$i];
            $pbc->save();
        }

        if($pbc){
            toastr()->success('!Registro guardado exitosamente!');
            //event(new OrderNotificationsEvent($notification));
            return redirect()->back();
        }else{
            toastr()->error('Existio un error al guardar el registro');
            return redirect()->back()->withInput();
        }
    }



    public function update(Request $request)
    {
        $id        = $request->arp_id;
        $brands    = $request->brands;

        $pbc = BusinessArp::where('arp_id',$id)->delete();

        for ($i=0; $i < sizeof($brands); $i++){
            $pbc                    = new BusinessArp();
            $pbc->arp_id            = $id;
            $pbc->brand_id          = $request->brands[$i];
            $pbc->pbc               = $request->pbc[$i];
            $pbc->save();
        }

        toastr()->success('!Registro guardado exitosamente!');
        return redirect()->back();


    }


    public function getPbcArp(Request $request)
    {
       // return $request;
        $pbcArp = [];
        $pbcs = BusinessArp::whereNotNull('pbc')->where('arp_id',$request->id)->get();


        foreach ($pbcs as $pbc) {
            $response = [];
            $brand_name = Brands::where('id_brand',$pbc->brand_id)->pluck('brand_name')->first();
            $response["id"]             = $pbc->id;
            $response["idBrand"]        = $pbc->brand_id;
            $response["brand"]          = $brand_name;
            $response["pbc"]         = $pbc->pbc;

            array_push($pbcArp, $response);
        }

        return $pbcArp;
    }
}
