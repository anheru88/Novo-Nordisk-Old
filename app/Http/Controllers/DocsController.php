<?php

namespace App\Http\Controllers;

use App\Client_File;
use App\DocRepository;
use App\FolderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Validator;


class DocsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$docs = DocRepository::orderBy('doc_name','DESC')->get();
        //dd($clients);
        /*$path = public_path().'/uploads';
        //dd($path);

        $files = File::directories($path);

        dd($files);*/
        return view('admin.files.index');
    }

    public function indexdocs(){
        $folders = FolderRepository::where('id_parent',0)->orderBy('folder_name','ASC')->get();
        $folders_child = FolderRepository::where('id_parent',0)->orderBy('folder_name','ASC')->get();
        $docs = DocRepository::where('id_folder',0)->orderBy('doc_name','ASC')->get();
        $parent = 0;
        $url = '';
        $breadcrumbs = '';
        return view('admin.files.indexgeneric',compact('folders','docs','parent','url','folders_child','breadcrumbs'));
    }


    public function createFolder(Request $request)
    {
        $folder = $request->folder_name;
        $url = $request->url;
        $path = public_path().'/uploads/'.$url.'/'.$folder;

        $foldernew = new FolderRepository();
        $foldernew->folder_name =  $folder;
        $foldernew->folder_url  =  $url.'/'.$folder;
        $foldernew->id_parent   =  $request->parent;

        if($foldernew->save()){
            File::makeDirectory($path, $mode = 0777, true, true);
            return redirect()->back()->with('success','Carpeta creada exitosamente');
        }else{
            return redirect()->back()->with('error','Existio un error al crear la carpeta')->withInput();
        }
    }

    public function createFile(Request $request)
    {
        $parent = $request->parent;
        $folder = $request->url;
        $hasFile = $request->hasFile('docs');
        $files = $request->file('docs');

        if($hasFile){
            $path = public_path().'/uploads/'.$folder;
            foreach($files as $file){
                $fileName = $file->getClientOriginalName();
                $fileReg = new DocRepository();
                $fileReg->id_folder     = $parent;
                $fileReg->doc_name      = $fileName;
                if($fileReg->save()){
                    $file->move($path, $fileName);

                }else{
                  //  return redirect()->back()->with('error','Existio un error al subir el archivo');
                }
            }
        }
        return redirect()->back()->with('success','Archivo subido exitosamente');
    }


    public function viewFolder($id)
    {
        $folders        = FolderRepository::where('id_parent',0)->orderBy('folder_name','ASC')->get();
        $folder         = FolderRepository::where('id_folder',$id)->first();
        $folders_child  = FolderRepository::where('id_parent',$folder->id_folder)->orderBy('folder_name','DESC')->get();
        $docs           = DocRepository::where('id_folder',$id)->orderBy('doc_name','ASC')->get();
        $parent         = $folder->id_folder;
        $url            = $folder->folder_url;
        $parentid       = $folder->id_parent;
        $breadcrumbs    = "";
        $parentName = "";

        if($parentid > 0){
            while($parentid > 0){
                $getParentname      = FolderRepository::where('id_folder',$parentid)->first();
                $parentid           = $getParentname->id_parent;
                $parentName         = "<a href=".$getParentname->id_folder.">".$getParentname->folder_name."</a>"."/";
                $breadcrumbs        = $parentName.$breadcrumbs;
            }
        }

        $breadcrumbs    .= $folder->folder_name;
        return view('admin.files.indexgeneric',compact('folders','docs','parent','folder','url','folders_child','breadcrumbs'));
    }



    public function editFolder(Request $request)
    {
        $idFolder = $request->id_folder;
        $folder = FolderRepository::where('id_folder',$idFolder)->first();
        $folder->folder_name = $request->folder_name;
        if($folder->update()){
            return redirect()->back()->with('success','Carpeta modificada existosamente');
        }else{
            return redirect()->back()->with('error','Existio un error al modificar el nombre');
        }
    }

    public function destroyFolder($id)
    {
        $folderDel = FolderRepository::where('id_folder',$id)->first();
        $folder = $folderDel->folder_name;
        $path = public_path().'/uploads/'.$folder;

        if (File::exists($path)) {
            File::deleteDirectory($path);
        }

        $folderDel->delete();
        return redirect()->back()->with('info','Carpeta '.$folder.' eliminada satisfactoriamente');
    }


    public function destroy($id)
    {
        $fileDel = DocRepository::where('id_doc',$id)->first();
        $folderR = FolderRepository::where('id_folder',$fileDel->id_doc)->first();
        //dd($folderR);
        $filename = $fileDel->file_name;
        if($folderR != ""){
            $folder = $folderR->folder_url;
            $path = public_path().'/uploads/'.$folder.'/'.$filename;
        }else{
            $path = public_path().'/uploads/'.'/'.$filename;
        }

        if (File::exists($path)) {
            File::delete($path);
        }
        $fileDel->delete();

        return redirect()->back()->with('info','Documento '.$filename.' eliminado satisfactoriamente');
    }
}
