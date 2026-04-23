<?php

namespace App\Http\Controllers;

use App\DocFormat;
use App\DocFormatCertificate;
use App\DocFormatType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DocFormatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ftypes = DocFormatType::orderBy('id_formattype', 'ASC')->get();
        return view('admin.formats.index', compact('ftypes'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexbytype(Request $request)
    {
        $ftype    = DocFormatType::where('id_formattype', $request->format)->first();
        $docs     = DocFormat::where('id_formattype', $request->format)->get();
        $docsCer  = DocFormatCertificate::where('id_formattype', $request->format)->get();
        return view('admin.formats.index_type', compact('docs', 'docsCer', 'ftype'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.formats.create_cot', compact('doc', 'ftype'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isEditor = auth()->user()->hasPermissionTo('users.create');
        if ($isEditor) {
            if ($request->formattype       == '1') {
                $format = new DocFormat();
                $format->id_formattype = $request->formattype;
                $format->name         = $request->name;
                $format->active        = '1';
                $format->save();
            } else if($request->formattype == '5') {
                $format = new DocFormatCertificate();
                $format->id_formattype = $request->formattype;
                $format->reference     = $request->reference;
                $format->active        = '1';
                $format->save();
            }
            return redirect()->back()->with('info', 'Se creo el nuevo formato correctamente');
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
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
     public function edit_cot($id_formattype, $idtype)
     {
         if ($idtype == '1') {
             $doc   = DocFormat::where('id_formattype', $idtype)->where('id_formattype', $id_formattype)->first();
             $ftype = DocFormatType::where('id_formattype', $doc->id_formattype)->first();
             return view('admin.formats.edit_cot', compact('doc', 'ftype'));
         }  else {
             return view('admin.formats.index', compact('doc', 'ftype'));
         }
     }
    


    public function edit_cer($id_format, $idtype){
        if ($idtype == '5') {
            $doc   = DocFormatCertificate::where('id_formattype', $idtype)->where('id', $id_format)->first();
            $ftype = DocFormatType::where('id_formattype', $doc->id_formattype)->first();
            return view('admin.formats.edit_cer', compact('doc', 'ftype'));
        } else {
            return view('admin.formats.index');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $idtype)
    {
        if ($idtype == '1') {
            $hasFile            = $request->hasFile('sign_image');
            if ($hasFile != "") {
                $files          = $request->file('sign_image');
                $path           = public_path() . '/uploads/';
                if ($hasFile) {
                    $fileName   = $files->getClientOriginalName();
                    $files->move($path, $fileName);
                    $path_image = $fileName;
                } else {
                    $path_image = "";
                }
            }

            $format = DocFormat::where('id_format', $id)->first();
            $format->name = $request->name;
            $format->conditions_time        = $request->conditions_time;
            $format->conditions_content     = $request->conditions_content;
            $format->conditions_special     = $request->conditions_special;
            $format->sign_name              = $request->sign_name;
            $format->sign_charge            = $request->sign_charge;
            if ($hasFile != "") {
                $format->sign_image         = $path_image;
            }
            $format->footer                 = $request->footer;
            $format->active                 = 1;

            if ($format->update()) {
                return redirect()->route('formats.index')->with('info', 'Formato modificado satisfactoriamente');
            } else {
                return redirect()->route('formats.index')->with('error', 'Existio un problema al modificar el formato');
            }
        } else if ($idtype == '5') {
            $hasFile            = $request->hasFile('user_firm');
            if ($hasFile != "") {
                $files          = $request->file('user_firm');
                $path           = public_path() . '/uploads/';
                if ($hasFile) {
                    $fileName   = $files->getClientOriginalName();
                    $files->move($path, $fileName);
                    $path_image = $fileName;
                } else {
                    $path_image = "";
                }
            }
            $format = DocFormatCertificate::where('id', $id)->first();
            $format->country          = $request->country;
            $format->reference        = $request->reference;
            $format->header_body      = $request->header_body;
            $format->body             = $request->body;
            $format->footer_body      = $request->footer_body;
            if ($hasFile != "") {
                $format->user_firm    = $path_image;
            }
            $format->user_name        = $request->user_name;
            $format->user_position    = $request->user_position;
            $format->page_name        = $request->page_name;
            $format->footer_column1_1 = $request->footer_column1_1;
            $format->footer_column1_2 = $request->footer_column1_2;
            $format->footer_column1_3 = $request->footer_column1_3;
            $format->footer_column2_1 = $request->footer_column2_1;
            $format->footer_column3_1 = $request->footer_column3_1;
            $format->active           = 1;
            if ($format->update()) {
                return redirect()->route('formats.index')->with('info', 'Formato modificado satisfactoriamente');
            } else {
                return redirect()->route('formats.index')->with('error', 'Existio un problema al modificar el formato');
            }
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
        //
    }


    public function uploadImage(Request $request)
    {



        if ($request->hasFile('upload')) {
            //get filename with extension

            /* $file = $request->file('upload');
            $fileName = $file->getClientOriginalName();
            $folder = "news";
            $path = public_path().'/uploads/'.$folder;
            File::makeDirectory($path, $mode = 0777, true, true);
            $filenametostore = $fileName;
            $file->move($path, $fileName);
*/
            if ($files = $request->file('upload')) {
                $destinationPath = public_path() . '/uploads/'; // upload path
                $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
                $files->move($destinationPath, $profileImage);
            }


            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('/uploads/' . $profileImage);
            $msg = 'Image subida satisfactoriamente';
            $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }
    }
}

