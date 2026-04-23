<?php

namespace App\Http\Controllers;

use App\Arp;
use App\ArpService;
use App\Brands;
use App\Http\Controllers\Controller;
use App\ServiceArp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceArpController extends Controller
{
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'required|max:100|unique:nvn_services_arp',
        ]);

        if ($validation->fails()) {
            toastr()->error('El servicio que quiere guardar ya existe');
            return redirect()->back()->withInput();
        }else{
            $arp        = $request->arp;
            $brands     = $request->brandsNumber;
            $serviceArp = new ServiceArp();
            $serviceArp->name       = $request->name;
            $serviceArp->arps_id    = $arp;
            if ($serviceArp->save()) {
                for ($i=0; $i < $brands; $i++){
                    $brand = "brand-".$i;
                    $volume = "volume-".$i;
                    $value = "value-cop-".$i;
                    $services                   =  new ArpService();
                    $services->services_arp_id  = $serviceArp->id;
                    $services->brand_id         = $request->$brand;
                    $services->volume           = ceil($request->$volume);
                    $services->value_cop        = ceil($request->$value);
                    $services->save();
                }
                toastr()->success('!Registro guardado exitosamente!');
                return redirect()->back();
            }
        }
    }

    public function getServiceArp(Request $request)
    {
        $servicesArp = [];
        $service = ServiceArp::where('id',$request->id)->first();

        foreach ($service->servicesData as $key => $service) {
            $response = [];
            $brand_name = Brands::where('id_brand',$service->brand_id)->pluck('brand_name')->first();
            $response["id"]             = $service->id;
            $response["idBrand"]        = $service->brand_id;
            $response["brand"]          = $brand_name;
            $response["volume"]         = $service->volume;
            $response["valueCop"]       = $service->value_cop;

            array_push($servicesArp, $response);
        }

        return $servicesArp;
    }


    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'      => "required|max:100|",
        ]);

        if ($validation->fails()) {
            toastr()->error('El servicio que quiere guardar ya existe');
            return redirect()->back()->withInput();
        }else{

            $id        = $request->arp;
            $brands    = $request->brands;

            $arp = ServiceArp::find($id)->update(['name' => $request->name]);
            $serviceArp = ArpService::where('services_arp_id',$id)->delete();

            for ($i=0; $i < sizeof($brands); $i++){
                $services                   = new ArpService();
                $services->services_arp_id  = $id;
                $services->brand_id         = $request->brands[$i];
                $services->volume           = ceil($request->volume[$i]);
                $services->value_cop        = ceil($request->value[$i]);
                $services->save();
            }

            toastr()->success('!Registro guardado exitosamente!');
            return redirect()->back();

        }
    }

    public function destroy($id)
    {
        ServiceArp::find($id)->delete();
        toastr()->success('¡Registro eliminado exitosamente!');
        return redirect()->back();
    }
}
