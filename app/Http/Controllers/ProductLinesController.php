<?php

namespace App\Http\Controllers;

use App\Product_Line;
use Illuminate\Http\Request;

class ProductLinesController extends Controller
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
            $lines = Product_Line::orderBy('line_name','DESC')->get();
            return view('admin.products.productLines', compact('lines'));
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
            $line = new Product_Line();
            $line->line_name     =   $request->nombre_linea;

            if($line->save()){
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
            $line = Product_Line::where('id_line',$id)->first();
            $line->line_name     =   $request->nombre_linea;

            if($line->save()){
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
        $isEditor = auth()->user()->hasPermissionTo('pproductlines.index');
        if($isEditor){
        $line = Product_Line::find($id);
        $line->delete();
        toastr()->success('!Registro eliminado exitosamente!');
        return redirect()->back();
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }
}
