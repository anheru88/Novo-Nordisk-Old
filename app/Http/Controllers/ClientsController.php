<?php

namespace App\Http\Controllers;

use App\Channel_Types;
use App\Client;
use App\Client_File;
use App\Client_Sap_Codes;
use App\Client_Types;
use App\ClientxProductScale;
use App\DocFormatCertificate;
use App\Events\OrderNotificationsEvent;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;
use App\Location;
use App\NegotiationDetails;
use App\Notifications;
use App\PaymentTerms;
use App\QuotationDetails;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

// Mensajes Session

class ClientsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isEditor = auth()->user()->hasPermissionTo('clients.index');
        if ($isEditor) {
            $roles = auth()->user()->roles;

            $rol = $roles[0]->slug;
            if ($roles[0]->slug == "cam") {
                $clientes = Client::where('id_diab_contact', auth()->user()->id)->orderBy('id_client', 'DESC')->with('city', 'channel', 'payterm', 'type')->get();
            } else {
                $clientes = Client::orderBy('id_client', 'DESC')->with('city', 'channel', 'payterm', 'type')->get();
            }
            //dd($clientes[5]);
            return view('admin.clients.index', compact('clientes', 'rol'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEditor = auth()->user()->hasPermissionTo('clients.create');

if ($isEditor) {
    $locations = Location::all();
    $departments = Location::getDepartments();
    $forma_pagos = PaymentTerms::all();
    $client_types = Client_Types::all();
    $dist_channels = Channel_Types::all();
    $roles = auth()->user()->roles;
    $usuarios = [];

    if (!empty($roles) && isset($roles[0]) && isset($roles[0]->slug)) {
        $roleSlug = $roles[0]->slug;

        if ($roleSlug == "admin" || $roleSlug == "admin_venta" || $roleSlug == "financiero" || $roleSlug == "autorizador") {
            $allUser = User::orderBy('name', 'ASC')->with('roles')->get();

            foreach ($allUser as $key => $user) {
                if (isset($user->roles[0]->slug) && $user->roles[0]->slug == 'cam') {
                    array_push($usuarios, $user);
                }
            }
        } else {
            $usuarios = User::where('id', Auth::user()->id)->get();
        }
    } else {
        abort(403, 'Acción no autorizada.');
    }

    return view('admin.clients.create', compact('locations', 'client_types', 'usuarios', 'departments', 'dist_channels', 'forma_pagos'));
} else {
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
        $isEditor = auth()->user()->hasPermissionTo('clients.create');
        if ($isEditor) {
            // Validación de datos del formulario
            $validation = Validator::make($request->all(), [
                'client_name'       => 'required',
                'id_client_type'    => 'required',
                'client_nit'        => 'required|min:7',
                'client_phone'      => 'required',
                'client_email'      => 'required|email',
                'client_contact'    => 'required',
                'id_diab_contact'   => 'required',
                'client_sap_code'   => 'unique:nvn_clients'
            ]);
            if ($validation->fails()) {
                return redirect('clients/create')
                    ->withErrors($validation)
                    ->withInput();
            }

            $client =  new Client();
            $client->id_client_type             = $request->id_client_type;
            $client->client_name                = strtoupper($request->client_name);
            $client->client_quote_name          = $request->client_quote_name;
            $client->client_nit                 = $request->client_nit;
            $client->client_sap_name            = $request->client_sap_name;
            $client->client_sap_code            = $request->client_sap_code;
            $client->id_client_channel          = $request->id_client_channel;
            $client->id_department              = $request->id_department;
            $client->id_city                    = $request->id_city;
            $client->client_contact             = ucwords(strtolower($request->client_contact));
            $client->client_address             = $request->client_address;
            $client->client_position            = $request->client_position;
            $client->client_area_code           = $request->client_area_code;
            $client->client_phone               = $request->client_phone;
            $client->client_email               = strtolower($request->client_email);
            $client->client_credit              = $request->client_credit;
            $client->client_contact             = $request->client_contact;
            $client->id_diab_contact            = $request->id_diab_contact;
            $client->id_biof_contact            = $request->id_biof_contact;
            $client->client_email_secondary     = strtolower($request->client_email_second);
            $client->id_payterm                 = $request->id_payterm;
            $client->active                     = $request->active;
            $client->created_by                 = $request->id_diab_contact;
            if ($client->save()) {
                $hasFile = $request->hasFile('docs');
                $files = $request->file('docs');
                $folder = Client::getClientID($request->client_nit);
                $path = public_path() . '/uploads/' . $folder;
                File::makeDirectory($path, $mode = 0777, true, true);
                if ($hasFile) {
                    $path = public_path() . '/uploads/' . $folder;
                    foreach ($files as $file) {
                        $fileName = $file->getClientOriginalName();
                        $fileReg = new Client_File();
                        $fileReg->id_client     = $folder;
                        $fileReg->file_folder   = $folder;
                        $fileReg->file_name     = $fileName;

                        if ($fileReg->save()) {
                            $file->move($path, $fileName);
                        }
                    }
                }
                $users_notified = User::whereHas(
                    'roles',
                    function ($q) {
                        $q->where('slug', 'financiero');
                    }
                )->get();

                $notiUsers = [];
                foreach ($users_notified as $user) {
                    $notification = Notifications::create([
                        'destiny_id'    => $user->id,
                        'sender_id'     => Auth()->user()->id,
                        'type'          => 'Creación de cliente',
                        'data'          => 'Se ha creado el cliente ' . $client->client_name,
                        'url'           => "/clients/$client->id_client",
                        'readed'        => 0,
                    ]);
                    array_push($notiUsers, $user->id);
                }
                $not['description']    = 'Se ha creado el cliente ' . $client->client_name;
                $not['userId']         = $notiUsers;
                $not['url']            = "/clients/$client->id_client";
                event(new OrderNotificationsEvent($not));
                return redirect()->route('clients.create')->with('success', 'Cliente guardado exitosamente');
            } else {
                return redirect()->back()->with('error', 'Existio un error al guardar el registro')->withInput();
            }
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('clients.show');
        if ($isEditor) {
            $folders = Client_File::where('id_client', $id)->with('clients')->get();
            $client = Client::where('id_client', $id)->with('city', 'department', 'cotizaciones', 'payterm', 'channel', 'files')->first();
            $client_type = Client_Types::first();
            $client_channel = Channel_Types::first();
            $locations   = Location::orderBy('id_locations', 'ASC')->pluck('loc_name', 'id_locations');
            $contacto    = User::first();

            $quotations  =  QuotationDetails::orderBy('id_quotation', 'DESC')
                ->where('id_client', $id)
                ->where(function($query){
                    $query->where('is_valid', 1)
                    ->orWhere('is_valid', 6);
                })
                ->whereHas('quotation', function ($query) {
                    $q = $query->where('is_authorized', 6)
                    ->orWhere('status_id', '=', 6);
                    return $q;
                })
                ->with('product', 'payterm', 'quotation')
                ->get();

                $negodetails = NegotiationDetails::where('id_client', $id)
                ->whereIn('is_valid', range(1, 7)) // Condiciones para is_valid entre 1 y 7
                ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_negotiations_details.id_product')
                ->orderBy('nvn_products.prod_name')
                ->orderBy('nvn_negotiations_details.id_negotiation_det')
                ->select('nvn_negotiations_details.*')
                ->get();

            $cam = User::where('id', $client->id_diab_contact)->first();


            return view('admin.clients.show', compact('id', 'folders', 'client', 'client_type', 'client_channel', 'locations', 'contacto', 'quotations', 'cam', 'negodetails'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    public function reportClient()
    {
        $fecha = Carbon::now();
        $export = new ClientsExport();
        return Excel::download($export, 'Reporte_Novo_Clientes_' . $fecha . '.xlsx');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('clients.edit');
        if ($isEditor) {
            $cliente          = Client::where('id_client', $id)->with('city', 'department', 'cotizaciones', 'payterm', 'channel', 'files')->first();
            $client_types     = Client_Types::orderBy('id_type', 'ASC')->pluck('type_name', 'id_type');
            $locations        = Location::where('loc_codecity', 0)->orderBy('loc_name', 'ASC')->pluck('loc_name', 'id_locations');
            $getlocation      = Location::where('id_locations', $cliente->id_department)->first();
            $dist_channels    = Channel_Types::orderBy('id_channel', 'ASC')->pluck('channel_name', 'id_channel');
            $cities           = Location::where('loc_codecity', $getlocation->loc_codedep)->orderBy('loc_name', 'ASC')->pluck('loc_name', 'id_locations');
            $sap_code         = Client_Sap_Codes::where('id_client', $id)->get();
            $forma_pagos      = PaymentTerms::orderBy('id_payterms', 'ASC')->pluck('payterm_name', 'id_payterms');
            $usuarios         = Role::where('slug', 'cam')->first()->users()->pluck('users.name', 'users.id');
            $contactoDiab     = User::getUsers($cliente->id_diab_contact)->first();
            $contactoBio      = User::getUsers($cliente->id_biof_contact)->first();
            $clientProducts   = QuotationDetails::getProductsAll($id);
            return view('admin.clients.edit', compact(
                'cliente',
                'client_types',
                'locations',
                'usuarios',
                'contactoDiab',
                'contactoBio',
                'cities',
                'sap_code',
                'forma_pagos',
                'dist_channels',
                'clientProducts'
            ));
        } else {
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
        //dd($request);
        $isEditor = auth()->user()->hasPermissionTo('clients.edit');
        if ($isEditor) {
            $scales = $request->escalas;
            $productScale = $request->productScale;
            //dd(ucwords(strtolower($request['client_contact'])));
            $client = Client::find($id);
            $client->client_name                = strtoupper($request['client_name']);
            $client->client_nit                 = $request['client_nit'];
            $client->id_client_type             = $request['id_client_type'];
            $client->id_department              = $request['id_department'];
            $client->id_client_channel          = $request['id_client_channel'];
            $client->id_city                    = $request['id_city'];
            if (!is_null($request['client_sap_code'])) {
                $client->client_sap_code        = $request['client_sap_code'];
            }
            $client->client_contact             = ucwords(strtolower($request['client_contact']));
            $client->client_address             = strtoupper($request['client_address']);
            $client->client_position            = $request['client_position'];
            $client->client_area_code           = $request['client_area_code'];
            $client->client_phone               = $request['client_phone'];
            $client->client_email               = strtolower($request['client_email']);
            $client->id_diab_contact            = $request['id_diab_contact'];
            $client->id_biof_contact            = $request['id_biof_contact'];
            $client->client_email_secondary     = strtolower($request['client_email_second']);

            if ($request['id_payterm'] != "") {
                $client->id_payterm                 = $request['id_payterm'];
            }
            if ($request['client_credit'] != "") {
                $client->client_credit              = $request['client_credit'];
            }
            if ($request['active'] != "") {
                $client->active                     = $request['active'];
            }

            if ($client->update()) {
                if ($scales != "") {
                    if (sizeof($scales) > 0) {
                        $deleteScales = ClientxProductScale::where('id_client', $id)->delete();
                        foreach ($scales as $key => $scale) {
                            if ($productScale[$key] != "" && $scales[$key] != "") {
                                $scaleClient = new ClientxProductScale();
                                $scaleClient->id_client     = $id;
                                $scaleClient->id_product    = $productScale[$key];
                                $scaleClient->id_scale      = $scales[$key];
                                $scaleClient->save();
                            }
                        }
                    }
                }

                $users_notified = User::whereHas(
                    'roles',
                    function ($q) {
                        $q->where('slug', 'admin_venta');
                    }
                )->get();

                $notiUsers = [];
                foreach ($users_notified as $user) {

                    $notification = new Notifications(); // Cambio: Usar "new" en lugar de "create"
                    $notification->destiny_id = $user->id;
                    $notification->sender_id = Auth()->user()->id;
                    $notification->type = 'Actualización de cliente';
                    $notification->data = 'Se ha actualizado el cliente ' . $client->client_name;
                    $notification->url = "/products/$client->id_client/edit";
                    $notification->readed = 0;
                    $notification->save(); // Cambio: Usar "save()" en lugar de "create()"

                    array_push($notiUsers, $user->id);
                }
                array_push($notiUsers,  $client->id_diab_contact);
                $not['description']    = 'Se ha actualizado el cliente ' . $client->client_name;
                $not['userId']         = $notiUsers;
                $not['url']            = "/products/$client->id_client/edit";
                event(new OrderNotificationsEvent($not));
                return redirect()->route('clients.edit', $client->id_client)->with('info', 'Cliente actualizado exitosamente');
            } else {
                return redirect()->route('clients.edit', $client->id_client)->with('info', 'Existio un problema con la aprobacion');
            }
        } else {
            abort(403, 'Acción no autorizada.');
        }


        return redirect()->route('clients.edit', $client->id_client)->with('info','Cliente actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('clients.delete');
        //dd($isEditor);
        if ($isEditor) {
            Client::find($id)->delete();
            return redirect()->route('clients.index')->with('info', 'Cliente eliminado satisfactoriamente');
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }


    public function getClient(Request $request)
    {
        //dd($request);
        //$client = Client::with('channel')->find($request->idClient);
        $client = Client::where('id_client', $request->idClient)->with('channel', 'payterm', 'city')
            ->get(['id_client', 'client_sap_code', 'id_department', 'id_city', 'id_client_channel', 'id_payterm']);
        return $client;
    }

    public function filesUpload(Request $request)
    {
        $hasFile = $request->hasFile('docs');
        $files = $request->file('docs');
        $folder = $request->id_client;
        if ($hasFile) {
            $path = public_path() . '/uploads/' . $folder;
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();

                $fileReg = new Client_File();
                $fileReg->id_client     = $folder;
                $fileReg->file_folder   = $folder;
                $fileReg->file_name     = $fileName;

                if ($fileReg->save()) {
                    $file->move($path, $fileName);
                }
            }
        }
        return redirect()->back()->with('success', 'Archivos guardados exitosamente');
    }

    public function clientsMasive(Request $request)
    {
        $hasFile = $request->hasFile('doc');
        if ($hasFile) {
            Excel::import(new ClientsImport, request()->file('doc'));
        } else {
            dd("no file");
        }
        toastr()->success('!Registro guardado exitosamente!');
        return redirect()->back();
    }

    public function createPDFCertificate($id, Request $request)
    {
        $idtype           = $request->idtype;
        $type             = $request->type;
        $cliente          = Client::where('id_client', $id)->with('city', 'department', 'payterm', 'users')->first();
        $client_types     = Client_Types::orderBy('id_type', 'ASC')->pluck('type_name', 'id_type');
        $client_sap_codes = Client_Sap_Codes::where('id_client', $id)->pluck('sap_code');
        $doc_cer          = DocFormatCertificate::where('id_formattype', $idtype)->first();
        $meses            = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $fecha            = Carbon::parse(date('d-m-Y'));
        $mes              = $meses[($fecha->format('n')) - 1];
        $date             = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
        $directedTo       = $request->directedto;
        // Cuerpo del formato
        $body = $doc_cer->body;
        $body = str_replace('@empresa', $cliente->client_name, $body);
        $body = str_replace('@nit', $cliente->client_nit, $body);
        if ($type == 'false') {
            if (!empty($cliente)) {
                $pdf = PDF::loadView('admin.clients.template.formato_1', compact('cliente', 'client_types', 'client_sap_codes', 'doc_cer', 'date', 'directedTo', 'body'))
                    ->setPaper('a4', 'landscape')
                    ->save('downloads/' . 'NOVO-' . $cliente->client_name . '.pdf');
                return $pdf->download('NOVO-' . $cliente->client_name . '.pdf', 'NOVO-' . $cliente->client_name . '.pdf', [], 'inline');
            }
        } else if ($type == 'true') {
            $fecha        = Carbon::parse($cliente->created_at);
            $mes          = $meses[($fecha->format('n')) - 1];
            $desde        = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
            if (!empty($cliente)) {
                $pdf = PDF::loadView('admin.clients.template.formato_2', compact('cliente', 'client_types', 'client_sap_codes', 'doc_cer', 'date', 'desde', 'directedTo', 'body'))
                    ->setPaper('a4', 'landscape')
                    ->save('downloads/' . 'NOVO-' . $cliente->client_name . '.pdf');
                return $pdf->download('NOVO-' . $cliente->client_name . '.pdf', 'NOVO-' . $cliente->client_name . '.pdf', [], 'inline');
            }
        }
    }
}

