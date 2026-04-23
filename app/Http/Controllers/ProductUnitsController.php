<?php

namespace App\Http\Controllers;

use App\MeasureUnit;
use Illuminate\Http\Request;

class ProductUnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('productlines.index');
        if($isEditor){
            $units = MeasureUnit::orderBy('unit_name','DESC')->get();
            return view('admin.products.productUnits', compact('units'));
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
        $isEditor = auth()->user()->hasPermissionTo('productlines.index');
        if($isEditor){
            $unit = new MeasureUnit();
            $unit->unit_name     =   $request->nombre_unit;

            if($unit->save()){
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
        $isEditor = auth()->user()->hasPermissionTo('productlines.index');
        if($isEditor){
            $unit = MeasureUnit::where('id_unit',$id)->first();
            $unit->unit_name     =   $request->nombre_unit;

            if($unit->save()){
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
        $isEditor = auth()->user()->hasPermissionTo('productlines.index');
        if($isEditor){
        $unit = MeasureUnit::find($id);
        $unit->delete();
        toastr()->success('!Registro eliminado exitosamente!');
        return redirect()->back();
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }
}
