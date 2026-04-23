<?php

namespace App\Http\Controllers;

use App\Client_Types;
use Illuminate\Http\Request;

class ClientTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('clientstype.index');
        if($isEditor){
            $clientstype = Client_Types::orderBy('type_name','DESC')->get();
            return view('admin.clients.indexclienttypes', compact('clientstype'));
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
        $isEditor = auth()->user()->hasPermissionTo('clientstype.index');
        if($isEditor){
            $clienttype = new Client_Types();
            $clienttype->type_name     =   $request->nombre_tipo;

            if($clienttype->save()){
                toastr()->success('!Registro guardado exitosamente!');
                return redirect()->back();
            }else{
                toastr()->error('Existio un error al guardar el registro');
                return redirect()->back()->withInput();
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
    public function update(Request $request, $id)
    {
        $isEditor = auth()->user()->hasPermissionTo('clientstype.index');
        if($isEditor){
            $clienttype = Client_Types::where('id_type',$id)->first();
            $clienttype->type_name     =   $request->nombre_tipo;

            if($clienttype->save()){
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('clientstype.index');
        if($isEditor){
        $clienttype = Client_Types::find($id);
        $clienttype->delete();
        toastr()->success('!Registro eliminado exitosamente!');
        return redirect()->back();
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }
}
