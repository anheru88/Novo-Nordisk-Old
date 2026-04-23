@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / Documentos del tipo {{ $ftype->format_name }} /
                {{ $doc->name }}
            </div>
            <h1>
                <i class="fas fa-book"></i> Documentos {{ $doc->name }}
            </h1>
        </section>
        <section class="content">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        {!! Form::model($doc,['route' => ['formats.update', $doc->id, $doc->id_formattype,$ftype->id_formattype], 'method' => 'PUT',
                        'files'=>'true']) !!}
                        <div class="col-xs-6">
                            <div class="quot-data-box">
                                <div class="form-group"> <label>Ciudad</label></div>
                                <div class="quot-data-box-content">
                                    {{ Form::text('country', $doc->country, ['class' => 'form-control', 'id'=>'country','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="form-group"> <label>Referencia</label></div>
                                <div class="input-group">
                                    {{ Form::text('reference', $doc->reference, ['class' => 'form-control', 'id'=>'reference','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="form-group"> <label>Cabecera del cuerpo</label></div>
                                <div class="input-group">
                                    {{ Form::text('header_body', $doc->header_body, ['class' => 'form-control', 'id'=>'header_body','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-group"> <label>Cuerpo del certificado</label> <br>
                                    Escriba @empresa para agregar el nombre de la empresa y @nit para agregar el número de NIT.
                                </div>
                                <div class="input-group">
                                    <textarea class="form-control" id="body" name="body">{{ $doc->body }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="form-group"> <label>Pie de página del cuerpo</label></div>
                                <div class="input-group">
                                    {{ Form::text('footer_body', $doc->footer_body, ['class' => 'form-control', 'id'=>'footer_body','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="content-divider"></div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group"> <label>Firmado por</label></div>
                            <div class="form-group"> <label>Firma del documento </label></div>
                            <div class="form-group">
                                Por favor suba la imagen de la firma
                            </div>
                            <div class="row quot-first-data" v-if="!editLogo" >
                                <div class="col-xs-12 col-sm-4 no-padding">
                                    <div class="col-sm-12 no-padding">
                                        <div class="logo img-responsive">
                                            <img src="{{ asset('/uploads/')."/".$doc->user_firm }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row quot-first-data" v-if="editLogo">
                                <div class="col-xs-12 col-sm-12 no-padding">
                                    <div class="col-sm-12 no-padding">
                                        {{-- <div class="file-loading"> --}}
                                            {!! Form::file ('user_firm', ['class'=>'file', 'type'=>'file', 'id'=>'file-sign']) !!}
                                        {{-- </div> --}}
                                    </div>
                                </div>
                                @error('imagen')
                                    <span class="invalid" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="editbtn">
                                <div class="btn btn-info float-right loadImage" v-if="!editLogo" v-on:click='editLogo=!editLogo'>Cambiar Imagen</div>
                                <div class="btn btn-info float-right loadImage" v-if="editLogo" v-on:click='editLogo=!editLogo'>Cancelar</div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="form-group"> <label>Nombre</label></div>
                                <div class="input-group">
                                    {{ Form::text('user_name', $doc->user_name, ['class' => 'form-control', 'id'=>'user_name','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="form-group"> <label>Cargo</label></div>
                                <div class="input-group">
                                    {{ Form::text('user_position', $doc->user_position, ['class' => 'form-control', 'id'=>'user_position','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="content-divider"></div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-group"> <label>Nombre de págnina que certifica</label></div>
                                <div class="input-group">
                                    {{ Form::text('page_name', $doc->page_name, ['class' => 'form-control', 'id'=>'page_name','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="content-divider"></div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="form-group"> <label>Pie de página columna uno(1) linea uno(1)</label></div>
                                <div class="input-group">
                                    {{ Form::text('footer_column1_1', $doc->footer_column1_1, ['class' => 'form-control', 'id'=>'footer_column1_1','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="form-group"> <label>Pie de página columna uno(1) linea dos(2)</label></div>
                                <div class="input-group">
                                    {{ Form::text('footer_column1_2', $doc->footer_column1_2, ['class' => 'form-control', 'id'=>'footer_column1_2','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="form-group"> <label>Pie de página columna uno(1) linea tres(3)</label></div>
                                <div class="input-group">
                                    {{ Form::text('footer_column1_3', $doc->footer_column1_3, ['class' => 'form-control', 'id'=>'footer_column1_3','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="content-divider"></div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="form-group"> <label>Pie de página columna dos(2) linea uno(1)</label></div>
                                <div class="input-group">
                                    {{ Form::text('footer_column2_1', $doc->page_name, ['class' => 'form-control', 'id'=>'page_name','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <div class="form-group"> <label>Link de pie de página columna tres(3) linea uno(1)</label></div>
                                <div class="input-group">
                                    {{ Form::text('footer_column3_1', $doc->footer_column3_1, ['class' => 'form-control', 'id'=>'footer_column3_1','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="content-divider"></div>
                        <div class="col-xs-12">
                            <div class="quot-send-btn">
                                {{ Form::button('Guardar formato', ['type' => 'submit', 'class' => 'btn btn-novo-big'] ) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection


@section('pagescript')
<script src="{{ asset('js/utilities.js') }}"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace( 'body', {
        language: 'es',
        width: 1200,
        filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
    });

    // CKEDITOR.replace( 'summary-ckeditor-2', {
    //     language: 'es',
    //     width: 1200,
    //     filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
    //     filebrowserUploadMethod: 'form',
    // });

    // CKEDITOR.replace( 'summary-ckeditor-3', {
    //     language: 'es',
    //     width: 1200,
    //     filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
    //     filebrowserUploadMethod: 'form',
    // });
    $(".loadImage").click(function (e) {
        $('#file-sign').fileinput({
            allowedFileExtensions: ['jpg', 'png', 'gif','doc','pdf','xls','docx','xlsx'],
            browseClass: "btn btn-primary btn-block",
            maxFileSize: 4000,
            maxFileCount: 1,
            showUpload: false,
            showRemove: false,
            showCaption: false,
            browseOnZoneClick: true,
            showBrowse: false,
            showDrag:true,
            slugCallback: function (filename) {
                return filename.replace('(', '_').replace(']', '_');
            }
        });
    });
</script>
@endsection

