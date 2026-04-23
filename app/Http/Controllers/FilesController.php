<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Client_File;
use App\Client;
use App\DocRepository;
use App\FolderRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;

class FilesController extends Controller
{

    public function index()
    {
        $clients = Client::orderBy('client_name','DESC')->get();
        return view('admin.files.indexclients', compact('clients'));
    }

    public function edit($id)
    {
        $clients = Client::find($id);
        $folders = Client_File::where('id_client',$id)->with('clients')->get();
        return view('admin.files.edit', compact('folders','clients', 'id'));
    }

    public function sharedDocs(Request $request){
        $mensaje = $request->mensaje;
        $destinatario = $request->destinatario;
        $config_time =  $request->timelast;
        switch($config_time) {
                case('10min'): $config_time = now()->addMinutes(10);
                break;
                case('30min'): $config_time = now()->addMinutes(30);
                break;
                case('60min'): $config_time = now()->addMinutes(60);
                break;
                case('3hr'): $config_time = now()->addHours(3);
                break;
                case('8hr'): $config_time = now()->addHours(8);
                break;
                case('14hr'): $config_time = now()->addHours(14);
                break;
                case('1d'): $config_time = now()->addDay(1);
                break;
                case('2d'): $config_time = now()->addDay(2);
                break;
                case('5d'): $config_time = now()->addDay(5);
                break;
                case('1sem'): $config_time = now()->addWeeks(1);
                break;
                case('2sem'): $config_time = now()->addWeeks(2);
                break;
                case('1mes'): $config_time = now()->addMonth(1);
                break;
                case('2mes'): $config_time = now()->addMonth(2);
                break;
                case('infinito'): $config_time = now()->addYears(500);
        }
        $idrequest = $request->id;
        $file_id = $request->id_files;
        $files = Client_File::whereIn('id_files', $file_id)->get();
        $id = ['id_client' => $idrequest];
        $client = Client_File::select('id_client')->where("id_client","=",$id)->first();
        $idclient = $client->id_client;
        $fichas = $request->id_files;
        $url = URL::temporarySignedRoute('sharedfiles', $config_time, ['fichas' => $fichas]);
        return view('admin.files.shared', compact('fichas', 'url', 'files', 'idclient', 'destinatario', 'mensaje', 'config_time'));
    }

    public function sharedGenericDocs(Request $request){
        $mensaje = $request->mensaje;
        $destinatario = $request->destinatario;
        $config_time =  $request->timelast;
        switch($config_time) {
                case('10min'): $config_time = now()->addMinutes(10);
                break;
                case('30min'): $config_time = now()->addMinutes(30);
                break;
                case('60min'): $config_time = now()->addMinutes(60);
                break;
                case('3hr'): $config_time = now()->addHours(3);
                break;
                case('8hr'): $config_time = now()->addHours(8);
                break;
                case('14hr'): $config_time = now()->addHours(14);
                break;
                case('1d'): $config_time = now()->addDay(1);
                break;
                case('2d'): $config_time = now()->addDay(2);
                break;
                case('5d'): $config_time = now()->addDay(5);
                break;
                case('1sem'): $config_time = now()->addWeeks(1);
                break;
                case('2sem'): $config_time = now()->addWeeks(2);
                break;
                case('1mes'): $config_time = now()->addMonth(1);
                break;
                case('2mes'): $config_time = now()->addMonth(2);
                break;
                case('infinito'): $config_time = now()->addYears(500);
        }
        $idClient = 1;
        $filesShared = session()->get('files');
        $files = DocRepository::whereIn('id_doc', $filesShared)->with('folder')->get();
        $fichas = $request->id_files;
        $url = URL::temporarySignedRoute('sharedfiles', $config_time, ['fichas' => $filesShared]);
        return view('admin.files.shared', compact('filesShared', 'url', 'files', 'idClient', 'destinatario', 'mensaje', 'config_time'));
    }


    public static function sharedDocsSendEmail(Request $request)
    {
        $email = $request->destinatario;
        $mensaje = $request->mensaje;
        $config_time = $request->config_time;
        $url = $request->url;
        $from = Auth::user()->email;
        $name = Auth::user()->name;
        Mail::send('emails.shareddocs', compact('url', 'mensaje', 'config_time', 'email', 'from'), function ($msj) use ($email, $from, $name) {
            $msj->subject('Archivos compratidos por ' . $name . ' desde Novo Nordisk CAM Tool');
            $msj->cc($from);
            $msj->from($from);
            $msj->to($email);
        });

        if (count(Mail::failures()) > 0) {

            echo "There was one or more failures. They were: <br />";
            foreach (Mail::failures() as $email_address) {
                echo " - $email_address <br />";
            }
        } else {
            return redirect()->back()->with('success', 'Documentos compartidos exitosamente');
        }
    }

    public function addSharedFiles(Request $request){
        $file = $request->idFile;
        $sharedFiles = session()->get('files');
        $filesArray = [];
        if($sharedFiles != ""){
            if(in_array($file,$sharedFiles)) {
                return "added";
            } else {
                array_push($sharedFiles,$file);
                session()->put('files', $sharedFiles);
                return "ok";
            }
        }else{
            array_push($filesArray,$file);
            session()->put('files', $filesArray);
            return "ok";
        }
    }

    public function getSharedFiles(){
        $sharedFiles = session()->get('files');
        $files = DocRepository::orderBy('doc_name')->whereIn('id_doc',$sharedFiles)->get(['id_doc','doc_name']);
        return $files;
    }

    public function removeSharedFiles(Request $request){
        $sharedFiles = session()->get('files');
        if (($key = array_search( $request->idFile,$sharedFiles)) !== false) {
            unset($sharedFiles[$key]);
        }
        session()->forget('files');
        session()->put('files', $sharedFiles);
        $files = DocRepository::orderBy('doc_name')->whereIn('id_doc',$sharedFiles)->get(['id_doc','doc_name']);
        return $files;
    }

    public function destroy($id){

        $fileDel = Client_File::find($id);
        $folder = $fileDel->file_folder;
        $filename = $fileDel->file_name;
        $path = public_path().'/uploads/'.$folder.'/'.$filename;

        if (File::exists($path)) {
            File::delete($path);
        }

        Client_File::find($id)->delete();
        return redirect()->back()->with('info','Documento '.$filename.' eliminado satisfactoriamente');

    }

}


