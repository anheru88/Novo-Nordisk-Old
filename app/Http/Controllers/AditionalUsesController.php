<?php

namespace App\Http\Controllers;

use App\AditionalUses;
use Illuminate\Http\Request;

class AditionalUsesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('products.index');
        if($isEditor){
            $uses = AditionalUses::orderBy('id_use','DESC')->get();
            //dd($productos);
            return view('admin.products.productAditionalUses', compact('uses'));
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
        $isEditor = auth()->user()->hasPermissionTo('products.create');
        if($isEditor){
            $use = new AditionalUses();
            $use->use_name     =   $request->nombre_use;

            if($use->save()){
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
     * @param  \App\AditionalUses  $aditionalUses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $isEditor = auth()->user()->hasPermissionTo('products.edit');
        if($isEditor){
            $use = AditionalUses::where('id_use',$id)->first();
            $use->use_name     =   $request->nombre_use;

            if($use->save()){
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
     * @param  \App\AditionalUses  $aditionalUses
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('products.create');
        if($isEditor){
        $use = AditionalUses::find($id);
        $use->delete();
        toastr()->success('!Registro eliminado exitosamente!');
        return redirect()->back();
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }
}
