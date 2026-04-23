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
                        {!! Form::model($doc,['route' => ['formats.update', $doc->id_formattype,$ftype->id_formattype], 'method' => 'PUT','files'=>'true']) !!}
                        <div class="col-xs-12">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title"><strong> Nombre del formato </strong></div>
                                <div class="quot-data-box-content">
                                    {{ Form::text('name', $doc->title, ['class' => 'form-control', 'id'=>'name','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-group"> <label>Plazo de entrega</label></div>
                                <div class="input-group">
                                    {{ Form::text('conditions_time', $doc->conditions_time, ['class' => 'form-control', 'id'=>'conditions_time','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-group"> <label>Observaciones </label></div>
                                <div class="input-group">
                                    <textarea class="form-control" id="summary-ckeditor" name="conditions_content">{{ $doc->conditions_content }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="content-divider"></div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-group"> <label>Condición especial </label></div>
                                <div class="input-group">
                                    <textarea class="form-control" id="summary-ckeditor-2"
                                        name="conditions_special">{{ $doc->conditions_special }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="content-divider"></div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-group"> <label>Firmado por</label></div>
                                <div class="input-group">
                                    <label>Nombre</label>
                                    {{ Form::text('sign_name', $doc->sign_name, ['class' => 'form-control', 'id'=>'sign_name','required'])}}
                                </div>
                                <div class="input-group">
                                    <label>Cargo</label>
                                    {{ Form::text('sign_charge', $doc->sign_charge, ['class' => 'form-control', 'id'=>'sign_charge','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group"> <label>Firma del documento </label></div>
                            <div class="form-group">
                                Por favor suba la imagen de la firma
                            </div>
                            <div class="row quot-first-data" v-if="!editLogo" >
                                <div class="col-xs-12 col-sm-4 no-padding">
                                    <div class="col-sm-12 no-padding">
                                        <div class="logo img-responsive">
                                            <img src="{{ asset('/uploads/')."/".$doc->sign_image }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row quot-first-data" v-if="editLogo">
                                <div class="col-xs-12 col-sm-12 no-padding">
                                    <div class="col-sm-12 no-padding">
                                        <div class="file-loading">
                                            {!! Form::file ('sign_image', ['class'=>'file', 'type'=>'file', 'id'=>'file-sign']) !!}
                                        </div>
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
                        <div class="col-xs-12">
                        <div class="content-divider"></div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-group"> <label>Condiciones comerciales de NOVO NORDISK </label></div>
                                <div class="input-group">
                                    <textarea class="form-control" id="summary-ckeditor-3"
                                        name="footer">{{ $doc->footer }}</textarea>
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
    CKEDITOR.replace( 'summary-ckeditor', {
        language: 'es',
        width: 1200,
        filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
    });

    CKEDITOR.replace( 'summary-ckeditor-2', {
        language: 'es',
        width: 1200,
        filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
    });

    CKEDITOR.replace( 'summary-ckeditor-3', {
        language: 'es',
        width: 1200,
        filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
    });
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
