<?php

namespace App\Http\Controllers;

use App\Arp;
use App\ArpService;
use App\ArpSimulationDetail;
use App\ArpSimulations;
use App\Brands;
use App\Client;
use App\Exports\ArpSimulationsExport;
use App\Http\Controllers\Controller;
use App\Imports\ArpImport;
use App\Negotiation;
use App\NegotiationDetails;
use App\Product;
use App\ServiceArp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ArpSimulationsController extends Controller
{


    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('reportes.index');
        $simulations = ArpSimulations::orderBy('created_at', 'DESC')->get();
        $arp = Arp::orderBy('year','asc')->pluck('name','id');
        if ($isEditor) {
            return view('admin.arp.simulator', compact('simulations','arp'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function importArp(Request $request){

        $simulations = ArpSimulations::orderBy('created_at')->get();
        $hasFile  = $request->hasFile('doc');
        $file     = $request->file('doc');
        $fileName = $file->getClientOriginalName();
        $name     = pathinfo($fileName, PATHINFO_FILENAME);
        if ($hasFile) {
            $simulation = ArpSimulations::create([
                'simulation_name' => $name
            ]);
            if ($simulation) {
                try {
                    Excel::import(new ArpImport($simulation->id), request()->file('doc')); // Crea todos los objetos
                } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                    $remove =  ArpSimulations::where('id',$simulation->id)->delete();
                    $failures = $e->failures();
                    foreach ($failures as $failure) {
                        $errors = [
                            'row' => $failure->row() + 1,
                            'attribute' => $failure->attribute(),
                            'errors' => $failure->errors(),
                        ];
                    }
                    toastr()->error('Existe un error en la fila '. $errors['row'] .' del archivo, por favor revise que no tenga campos vacíos y que existe dentro del sistema');
                    return redirect()->back();
                }
            }
        } else {
            toastr()->error('Debe adjuntar un archivo valido');
            return redirect()->back();
        }
        toastr()->success('!Archivo importado exitosamente!');
        return redirect()->back();
    }


    public function generateExcel(Request $request)
    {
        $productsArp   = [];
        $arp = Arp::where('id',$request->arp)->with('services')->first();
        $brandsArp = ArpService::brandsVolume($arp->services[0]->id); // get Brands with volume and value-cop

        foreach ($brandsArp as $key => $brandArp) {
            array_push($productsArp, $brandArp->brand_id);
        }
        $date           = Carbon::now();
        $brands         = Brands::whereIn('id_brand',$productsArp)->get();
        $arp = new ArpSimulationsExport($request->id, $brands, $arp);
        return Excel::download($arp, 'ARP Simulations - ' . $date . '.xlsx');

    }

    public function destroy($id)
    {
        ArpSimulations::find($id)->delete();
        toastr()->success('¡Registro eliminado exitosamente!');
        return redirect()->back();
    }


}
