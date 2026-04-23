<?php

namespace App\Http\Controllers;

use App\Brands;
use App\Channel_Types;
use App\Client;
use App\Client_Types;
use App\Exports\ClientsExcelExport;
use App\Exports\InvoicesExport;
use App\Exports\NegotiationExport;
use App\Exports\QuotationExport;
use App\Imports\SalesImport;
use App\Location;
use App\Negotiation;
use App\NegotiationConcepts;
use App\NegotiationDetails;
use App\PaymentTerms;
use App\Product;
use App\QuotationDetails;
use App\Sales;
use App\Status;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('reportes.index');
        if ($isEditor) {
            $usuarios     = [];
            $results      = [];
            $brands       = Brands::orderBy('brand_name', 'ASC')->pluck('brand_name', 'id_brand');
            $clients      = Client::orderBy('client_name', 'ASC')->pluck('client_name', 'id_client');
            $type_clients = Client_Types::orderBy('type_name', 'ASC')->pluck('type_name', 'id_type');
            $departments  = Location::getDepartments();
            $forma_pagos  = PaymentTerms::get();
            $products     = Product::orderBy('prod_name', 'ASC')->pluck('prod_name', 'id_product');
            $channels     = Channel_Types::orderBy('channel_name', 'ASC')->pluck('channel_name', 'id_channel');
            $payterms     = PaymentTerms::orderBy('payterm_name', 'ASC')->pluck('payterm_name', 'id_payterms');
            $allUser      = User::orderBy('name', 'ASC')->with('roles')->get();
            $status       = Status::all();
            //dd($allUser);
            foreach ($allUser as $key => $user) {
                if (sizeof($user->roles) > 0) {
                    if ($user->roles[0]->slug == 'cam') {
                        array_push($usuarios, $user);
                    }
                }
            }
            return view('admin.reports.index', compact('status', 'clients', 'type_clients', 'products', 'usuarios', 'results', 'brands', 'channels', 'payterms', 'departments', 'forma_pagos'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClients(Request $request)
    {
        $clients = Client::where('id_diab_contact', $request->idUser)->get();
        return $clients;
    }

    public function search(Request $request)
    {
        $usuarios         = [];
        $isEditor         = auth()->user()->hasPermissionTo('reportes.index');
        $type             = $request->type;
        $idClient         = $request->client;
        $idClientType     = $request->typeclient;
        $idDepartment     = $request->id_department;
        $idCity           = $request->id_city;
        $idCanal          = $request->channel;
        $active           = $request->active;
        $idProduct        = $request->product;
        $idPayterm        = $request->payterm;
        $idChannel        = $request->channel;
        $idUser           = $request->user;
        $brand            = $request->brand;
        $dateIni          = $request->desde;
        $dateEnd          = $request->hasta;
        $excel            = $request->excel;
        $idStatus         = $request->id_status;
        $status           = Status::all();

        if ($isEditor) {
            $brands       = Brands::orderBy('brand_name', 'ASC')->pluck('brand_name', 'id_brand');
            $clients      = Client::orderBy('client_name', 'ASC')->pluck('client_name', 'id_client');
            $products     = Product::orderBy('prod_name', 'ASC')->pluck('prod_name', 'id_product');
            $channels     = Channel_Types::orderBy('channel_name', 'ASC')->pluck('channel_name', 'id_channel');
            $payterms     = PaymentTerms::orderBy('payterm_name', 'ASC')->pluck('payterm_name', 'id_payterms');
            $type_clients = Client_Types::orderBy('type_name', 'ASC')->pluck('type_name', 'id_type');
            $departments  = Location::getDepartments();
            $forma_pagos  = PaymentTerms::get();
            $allUser      = User::orderBy('name', 'ASC')->with('roles')->get();
            $oldReq       = $request->all();
            $fecha        = Carbon::now();
            foreach ($allUser as $key => $user) {
                if (sizeof($user->roles) > 0) {
                    if ($user->roles[0]->slug == 'cam') {
                        array_push($usuarios, $user);
                    }
                }
            }
            if ($type == 'cot') {
                $results = QuotationDetails::orderBy('id_quota_det', 'ASC');
                if ($idClient != "") {
                    $results = $results->where('id_client', $idClient);
                }
                if ($idProduct != "") {
                    $results = $results->where('id_product', $idProduct);
                }
                if ($idPayterm != "") {
                    $results = $results->where('id_payterm', $idPayterm);
                }
                if ($dateIni != "" || $dateEnd != "" || $idChannel != "" || $idUser != "" || $idStatus != "") {
                    $results = $results->whereHas('quotation', function ($query) use ($dateIni, $dateEnd, $idUser, $idChannel, $idStatus) {
                        if ($idStatus != "") {
                            $q = $query->where('status_id', $idStatus);
                        }
                        if ($dateIni != "") {
                            $q = $query->where('quota_date_ini', '<=', $dateIni);
                            $q = $query->where('quota_date_end', '>=', $dateIni);
                        }
                        if ($dateEnd != "") {
                            $q = $query->where('quota_date_end', '<=', $dateEnd);
                        }
                        if ($idChannel != "") {
                            $q = $query->where('id_channel', $idChannel);
                        }
                        if ($idUser != "") {
                            $q = $query->where('created_by', $idUser); // filtro de fecha inicial y cierre de negociacion
                        }
                        return $q;
                    });
                }
                // $results = $results->where('is_valid', 1);
                $results = $results->with('payterm', 'quotation', 'client');
                $results = $results->get();
                if ($excel == "false") {
                    return view(
                        'admin.reports.index',
                        compact(
                            'clients',
                            'products',
                            'usuarios',
                            'results',
                            'brands',
                            'channels',
                            'payterms',
                            'oldReq',
                            'type',
                            'channels',
                            'idUser',
                            'idProduct',
                            'dateIni',
                            'dateEnd',
                            'idChannel',
                            'type_clients',
                            'departments',
                            'forma_pagos',
                            'idDepartment',
                            'idCity',
                            'idPayterm',
                            'status',
                            'active'
                        )
                    );
                } else {
                    ini_set('memory_limit','1024M');
                    set_time_limit(3000000);
                    return Excel::download(new QuotationExport($oldReq), 'Reporte_Novo_Cotizaciones_' . $fecha . '.xlsx');
                   // return Excel::download(new QuotationExport($oldReq), 'Reporte_Novo_Cotizaciones_' . $fecha . '.xlsx');
                }
            } elseif ($type == 'neg') {
                $results1     = NegotiationDetails::orderBy('id_negotiation_det', 'ASC');
                if ($idProduct != "") {
                    $results1 = $results1->where('id_product', $idProduct);
                }
                if ($dateIni != "" || $dateEnd != "" || $idChannel != "" || $idUser != "" || $idStatus != "") {
                    $results1 = $results1->whereHas('negotiation', function ($query) use ($dateIni, $dateEnd, $idUser, $idChannel, $idStatus) {
                        if ($idStatus != "") {
                            $q = $query->where('status_id', $idStatus);
                        }
                        if ($dateIni != "") {
                            $q = $query->where('negotiation_date_ini', '<=', $dateIni);
                            $q = $query->where('negotiation_date_end', '>=', $dateIni);
                        }
                        if ($dateEnd != "") {
                            $q = $query->where('negotiation_date_end', '<=', $dateEnd);
                        }
                        if ($idChannel != "") {
                            $q = $query->where('id_channel', $idChannel);
                        }
                        if ($idUser != "") {
                            $q = $query->where('created_by', $idUser); // filtro de fecha inicial y cierre de negociacion
                        }
                        return $q;
                    });
                }
                // $results1 = $results1->where('is_valid', 1);
                $results1 = $results1->with('payterm', 'negotiation', 'quotation', 'client', 'product');
                $results1 = $results1->take('1000');
                $results1 = $results1->get();
                if ($excel == "false") {
                    return view(
                        'admin.reports.index',
                        compact(
                            'clients',
                            'products',
                            'usuarios',
                            'results1',
                            'brands',
                            'channels',
                            'payterms',
                            'oldReq',
                            'type',
                            'channels',
                            'idUser',
                            'idProduct',
                            'dateIni',
                            'dateEnd',
                            'idChannel',
                            'type_clients',
                            'departments',
                            'forma_pagos',
                            'idDepartment',
                            'idCity',
                            'idPayterm',
                            'status',
                            'active'
                        )
                    );
                } else {
                    //return Excel::download(new NegotiationExport($oldReq), 'Reporte_Novo_Negociaciones_' . $fecha . '.xlsx');
                    return (new NegotiationExport($oldReq))->download('Reporte_Novo_Negociaciones_' . $fecha . '.xlsx');
                }
            } elseif ($type == 'cli') {
                $results2 = Client::orderBy('id_client', 'ASC');
                if ($idUser != "") {
                    $results2 = $results2->where('id_diab_contact', $idUser);
                }
                if ($idPayterm != "") {
                    $results2 = $results2->where('id_payterm', $idPayterm);
                }
                if ($idCanal != "") {
                    $results2 = $results2->where('id_client_channel', $idCanal);
                }
                if ($idClientType != "") {
                    $results2 = $results2->where('id_client_type', $idClientType);
                }
                if ($active != "") {
                    $results2 = $results2->where('active', $active);
                }
                if ($idDepartment != "") {
                    $results2 = $results2->where('id_department', $idDepartment);
                }
                if ($idCity != "") {
                    $results2 = $results2->where('id_city', $idCity);
                }
                // $results2 = $results2->where('is_valid', 1);
                $results2 = $results2->with('payterm', 'department', 'city', 'clientType', 'channel', 'users');
                $results2 = $results2->get();
                if ($excel == "false") {
                    return view(
                        'admin.reports.index',
                        compact(
                            'clients',
                            'products',
                            'usuarios',
                            'results2',
                            'brands',
                            'channels',
                            'payterms',
                            'oldReq',
                            'type',
                            'channels',
                            'idUser',
                            'idProduct',
                            'dateIni',
                            'dateEnd',
                            'idChannel',
                            'type_clients',
                            'departments',
                            'forma_pagos',
                            'idClientType',
                            'idDepartment',
                            'idCity',
                            'idPayterm',
                            'status',
                            'active'
                        )
                    );
                } else {
                    return Excel::download(new ClientsExcelExport($oldReq), 'Reporte_Novo_Clientes_' . $fecha . '.xlsx');
                }
            } else {
                $results1 = Negotiation::where('id_client', $idClient)->get();
            }
            return view('admin.reports.index', compact('clients', 'products', 'usuarios', 'results', 'brands', 'channels', 'payterms'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function notas(Request $request)
    {
        $isEditor = auth()->user()->hasPermissionTo('reportes.index');
        $filter = $request->query('filter');
        $quantity = $request->query('quantity');
        if($quantity == ""){
            $quantity = 20;
        }

        $sales = Sales::orderBy('created_at', 'DESC');
        if($filter != ""){
            $sales->where('doc_name', 'ILIKE', '%' . $filter . '%');
        }
        $sales->get();

        if ($isEditor) {
            $sales =  $sales->paginate($quantity);
            return view('admin.notas.index', compact('sales','quantity','filter'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function notasMasive(Request $request)
    {
        $quantity = 20;
        $filter = "";

        $hasFile  = $request->hasFile('doc');
        $file     = $request->file('doc');
        $fileName = $file->getClientOriginalName();
        $name     = pathinfo($fileName, PATHINFO_FILENAME);
        if ($hasFile) {
            $sale = Sales::create([
                'doc_name' => $name
            ]);

            if ($sale) {
                Excel::import(new SalesImport($sale->id_sales), request()->file('doc')); // Crea todos los objetos
            }
        } else {
            alert()->error('Debe adjuntar un archivo valido');
            return redirect()->back();
        }
        $sales = Sales::orderBy('created_at')->get();
        toastr()->success('!Ventas importadas exitosamente!');
        return redirect()->back();
    }

    public function notasDownload(Request $request)
    {
        $idScales      = 0;
        $idSale        = $request->id_sales;
        $fecha         = Carbon::now();
        $concepts      = [];
        $nameConcepts  = [];
        $conceptsQuery = NegotiationConcepts::orderBy('id_negotiation_concepts', 'ASC')->get();


        foreach ($conceptsQuery as $key => $concept) {
            array_push($concepts, $concept->id_negotiation_concepts);
            array_push($nameConcepts, $concept->name_concept);
        }




        array_unshift($concepts, 0); // ids de las escalas
        array_unshift($nameConcepts, 'Escalas');


        $export = new InvoicesExport($concepts, $nameConcepts, $idSale);

        return Excel::download($export, 'Reporte_Novo_' . $fecha . '.xlsx');
    }

    public function notasDestroy($id)
    {
        Sales::find($id)->delete();
        return redirect()->route('notas')->with('info', 'Nota eliminada satisfactoriamente');
    }

    public function destroy($id)
    {
        //
    }

    public function importExportView()
    {
        return view('importexport');
    }

    public function export(Request $request)
    {
        if (isset($request->client, $request->payterm)) {
            $type           = $request->type;
            $idClient       = $request->client;
            $idProduct      = $request->product;
            $idPayterm      = $request->payterm;
            $idChannel      = $request->channel;
            $idUser         = $request->user;
            $brand          = $request->brand;
            $dateIni        = $request->desde;
            $dateEnd        = $request->hasta;
            $excel          = $request->excel;
        } else {
            $type           = $request->type;
            // $idClient       = $request->client;
            $idProduct      = $request->product;
            // $idPayterm      = $request->payterm;
            $idChannel      = $request->channel;
            $idUser         = $request->user;
            // $brand          = $request->brand;
            $dateIni        = $request->desde;
            $dateEnd        = $request->hasta;
            $excel          = $request->excel;
        }
        $results = NegotiationDetails::orderBy('id_negotiation_det', 'ASC');
        if ($idProduct != "") {
            $results = $results->where('id_product', $idProduct);
        }
        if ($dateIni != "" || $dateEnd != "" || $idChannel != "" || $idUser != "") {
            $results = $results->whereHas('quotation', function ($query) use ($dateIni, $dateEnd, $idUser, $idChannel) {
                if ($dateIni != "") {
                    $q = $query->where('quota_date_ini', '<=', $dateIni);
                }
                if ($dateEnd != "") {
                    $q = $query->where('quota_date_end', '>=', $dateEnd);
                }
                if ($idChannel != "") {
                    $q = $query->where('id_channel', $idChannel);
                }
                if ($idUser != "") {
                    $q = $query->where('created_by', $idUser); // filtro de fecha inicial y cierre de negociacion
                }
                return $q;
            });
        }
        $results = $results->where('is_valid', 6);
        $results = $results->with('payterm', 'negotiation', 'quotation', 'client', 'concept', 'product');
        $results = $results->get();
        return Excel::download(new NegotiationExport($results), 'bulkData.xlsx');
    }
}
