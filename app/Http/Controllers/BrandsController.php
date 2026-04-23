<?php

namespace App\Http\Controllers;

use App\Brands;
use App\Events\OrderNotificationsEvent;
use App\Notifications\BrandNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandsController extends Controller
{

    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('clientstype.index');
        if($isEditor){
            $brands = Brands::orderBy('brand_name','DESC')->get();
            return view('admin.brands.index', compact('brands'));
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }


    public function store(Request $request)
    {
        $isEditor = auth()->user()->hasPermissionTo('clientstype.index');
        $notification = [];
        if($isEditor){
            /*$notification['description']    = "Se creo una nueva marca";
            $notification['url']            = "brands/";
            $notification['userId']         = [1];
            event(new OrderNotificationsEvent($notification));
            dd($notification);*/
            $brand = new Brands();
            $brand->brand_name     =   $request->nombre_marca;

            if($brand->save()){
                toastr()->success('!Registro guardado exitosamente!');
                //event(new OrderNotificationsEvent($notification));
                return redirect()->back();
            }else{
                toastr()->error('Existio un error al guardar el registro');
                return redirect()->back()->withInput();
            }
        }
    }

    public function update(Request $request, $id)
    {
        $isEditor = auth()->user()->hasPermissionTo('clientstype.index');
        if($isEditor){
            $brand = Brands::where('id_brand',$id)->first();
            $brand->brand_name     =   $request->nombre_marca;

            if($brand->save()){
                toastr()->success('!Registro guardado exitosamente!');
                return redirect()->back();
            }else{
                toastr()->error('Existio un error al guardar el registro');
                return redirect()->back()->withInput();
            }
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }


    public function destroy($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('clientstype.index');
        if($isEditor){
        $brand = Brands::where('id_brand',$id)->first();
        $brand->delete();
        toastr()->success('!Registro eliminado exitosamente!');
        return redirect()->back();
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }
}
