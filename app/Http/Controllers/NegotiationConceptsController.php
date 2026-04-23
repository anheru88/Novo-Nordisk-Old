<?php

namespace App\Http\Controllers;

use App\NegotiationConcepts;
use Illuminate\Http\Request;

class NegotiationConceptsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $isEditor = auth()->user()->hasPermissionTo('concept.index');
        if($isEditor){
            $concepts = NegotiationConcepts::all();
            return view('admin.conceptsn.index', compact('concepts'));
        }else{
            abort(403, 'Acción no autorizada.');
        }


    }


    public function store(Request $request)
    {
        $isEditor = auth()->user()->hasPermissionTo('concept.edit');
        if($isEditor){
            //dd($request);
            $concept = new NegotiationConcepts();
            $concept->name_concept          =   $request->name_concept;
            $concept->concept_percentage    =   $request->concept_percentage;
            $concept->concept_compress      =   $request->compress;
            $concept->sap_concept           =   $request->sap_concept;

            if($concept->save()){
                toastr()->success('!Registro guardado exitosamente!');
                return redirect()->back();
            }else{
                toastr()->error('Existio un error al guardar el registro');
                return redirect()->back()->withInput();
            }
        }
    }


    public function update(Request $request, $id)
    {
        //dd($request);
        $isEditor = auth()->user()->hasPermissionTo('concept.edit');
        if($isEditor){
            $concept = NegotiationConcepts::where('id_negotiation_concepts',$id)->first();
            $concept->name_concept          =   $request->name_concept;
            $concept->concept_percentage    =   $request->concept_percentage;
            $concept->concept_compress      =   $request->compress;
            $concept->sap_concept           =   $request->sap_concept;

            if($concept->save()){
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
        $isEditor = auth()->user()->hasPermissionTo('concept.edit');
        if($isEditor){
            $concept = NegotiationConcepts::find($id);
            $concept->delete();
            toastr()->success('!Registro eliminado exitosamente!');
            return redirect()->back();
        }else{
            abort(403, 'Acción no autorizada.');
        }
    }
}
