<?php

namespace App\Http\Controllers;

use App\PaymentTerms;
use Illuminate\Http\Request;

class PayMethodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('paymethods.index');
        if($isEditor){
            $paymethods = PaymentTerms::orderBy('payterm_name','DESC')->get();
            return view('admin.clients.indexpaymethods', compact('paymethods'));
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

        //dd($request);
        $isEditor = auth()->user()->hasPermissionTo('paymethods.index');
        if($isEditor){
            $payterm = new PaymentTerms();
            $payterm->payterm_name      =   $request->payterm_name;
            $payterm->payterm_percent   =   $request->payterm_percent;
            $payterm->payterm_code      =   $request->payterm_code;

            if($payterm->save()){
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

       // dd($request);
        $isEditor = auth()->user()->hasPermissionTo('paymethods.index');
        if($isEditor){
            $payterm = PaymentTerms::where('id_payterms',$id)->first();
            $payterm->payterm_name      =   $request->payterm_name;
            $payterm->payterm_percent   =   $request->payterm_percent;
            $payterm->payterm_code      =   $request->payterm_code;

            if($payterm->save()){
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
        $isEditor = auth()->user()->hasPermissionTo('paymethods.index');
        if($isEditor){
        $payterm = PaymentTerms::find($id);
        $payterm->delete();
        toastr()->success('!Registro eliminado exitosamente!');
        return redirect()->back();
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }
}
