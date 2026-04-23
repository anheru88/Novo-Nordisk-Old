<?php

namespace App\Http\Controllers;

use App\Arp;
use App\ArpService;
use App\Brands;
use App\Http\Controllers\Controller;
use App\ServiceArp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArpController extends Controller
{
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('reportes.index');
        $arps = Arp::orderBy('created_at', 'DESC')->get();
        if ($isEditor) {
            return view('admin.arp.index', compact('arps'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function store(Request $request){
        //dd($request);
        $isEditor = auth()->user()->hasPermissionTo('reportes.index');
        if ($isEditor) {
            // Validación de datos del formulario
            $validation = Validator::make($request->all(), [
                'name'      => 'required|max:100',
                'year'      => 'required|max:20',
                'std'       => 'required|max:20|unique:nvn_arps',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation);
            }else{
                $arp = Arp::create($request->all());
                if($arp){
                    toastr()->success('!Registro guardado exitosamente!');
                    //event(new OrderNotificationsEvent($notification));
                    return redirect()->back();
                }else{
                    toastr()->error('Existio un error al guardar el registro');
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    public function edit($id){
        $arp            = Arp::find($id);
        $brands         = Brands::orderBy('brand_name','asc')->get();
        $servicesArp    = ServiceArp::where('arps_id', $id)->get();
        $brands         = ArpService::where('services_arp_id', $servicesArp->id)->with('brands')->get();
        return view('admin.arp.edit', compact('arp','brands','servicesArp'));
    }

    public function destroy($id)
    {
        $arp = Arp::find($id);
        if (!$arp) {
            toastr()->error('El registro no existe');
            return redirect()->back();
        }
    
        $arp->delete();
        toastr()->success('¡Registro eliminado exitosamente!');
        return redirect()->back();
    }
    
}
