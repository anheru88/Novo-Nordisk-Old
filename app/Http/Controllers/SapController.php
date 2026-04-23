<?php

namespace App\Http\Controllers;

use App\CreditNotes;
use App\CreditNotesClients;
use App\Exports\SapExport;
use App\Http\Controllers\Controller;
use App\Imports\SapClientImport;
use App\Imports\SapImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class SapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $isEditor = auth()->user()->hasPermissionTo('reportes.index');
        $crenotes = CreditNotes::orderBy('created_at', 'DESC')->get();
        //$quantity = $request->query('quantity');
        //dd($crenotes);
        if ($isEditor) {
            return view('admin.notas.indexsap', compact('crenotes'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }
    // Importacion de notas
    public function importCreditNotes(Request $request){
        $hasFile  = $request->hasFile('doc');
        $file     = $request->file('doc');
        $fileName = $file->getClientOriginalName();
        $name     = pathinfo($fileName, PATHINFO_FILENAME);
        if ($hasFile) {
            $ncredit = CreditNotes::create([
                'doc_name' => $name
            ]);

            if($ncredit){
                $import = new SapImport($ncredit->id_credit_notes);
                $hoja_de_credito = $request->input('hoja_de_credito');
                //@dd($hoja_de_credito);
                $import->onlySheets($hoja_de_credito);
                 Excel::import($import, $file);
                return redirect()->route('sapnotes')->with('info', 'Notas cargadas satisfactoriamente');
            }else{
                return redirect()->route('sapnotes')->with('error', 'Error al cargar las notas');
            }
        } else {
            return redirect()->route('sapnotes')->with('error', 'Error al cargar las notas');
        }
    }

    // Creacion y descarga del archivo plano para SAP
    public function generateCsv(Request $request)
    {
        
        $fecha = Carbon::now();
        $notes = CreditNotes::where('id_credit_notes',$request->id_crenote)->with('products')

        //->groupBy()
        ->get();
        //dd($notes);
        /* $clients = CreditNotesClients::where('id_credit_notes',$request->id_crenote)
        ->groupBy('client_sap_code','concept')
        ->with('details')
        ->get('client_sap_code');
        dd($clients);*/
        $notesSize = sizeof($notes[0]->products);
        //dd($notesSize);
        $retorno = view('admin.notas.tablesap', ['notes' => $notes, 'notesSize' => $notesSize])->render();
        $fileName = ".txt";

        // use headers in order to generate the download
        $headers = [
        'Content-type' => 'text/plain',
        'Content-Disposition' => sprintf('attachment; filename="%s"', 'Reporte_SAP_'.$notes[0]->doc_name.$fecha.$fileName),
        ];

        // make a response, with the content, a 200 response code and the headers
        return Response::make($retorno, 200, $headers);
    }

    public function generateExcel(Request $request)
    {
        try {
            $fecha = Carbon::now();
            $export = new SapExport($request->id_crenote);
            return Excel::download($export, 'Reporte_SAP_' . $fecha . '.xlsx');
        } catch (\Exception $e) {
            // Log the error
            Log::error($e->getMessage());
            // Display a generic error message or handle the error gracefully
            return response()->json(['error' => 'An error occurred while generating the Excel file.']);
        }
    }
    
    public function destroy($id)
    {
        CreditNotes::find($id)->delete();
        return redirect()->route('sapnotes')->with('info', 'Nota eliminada satisfactoriamente');
    }
}

