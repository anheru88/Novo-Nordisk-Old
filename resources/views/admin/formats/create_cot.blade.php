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
                        {!! Form::model($doc,['route' => ['formats.update', $doc->id_format], 'method' => 'PUT',
                        'files'=>'true']) !!}
                        <div class="col-xs-12">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Nombre del formato</div>
                                <div class="quot-data-box-content">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'id'=>'name','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-group"> <label>Plazo de entrega</label></div>
                                <div class="input-group">
                                    {{ Form::text('conditions_time', null, ['class' => 'form-control', 'id'=>'conditions_time','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-group"> <label>Observaciones </label></div>
                                <div class="input-group">
                                    <textarea class="form-control" id="summary-ckeditor" name="conditions_content"></textarea>
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
                                    <textarea class="form-control" id="summary-ckeditor-2"  name="conditions_special"></textarea>
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
                                    {{ Form::text('sign_name', null, ['class' => 'form-control', 'id'=>'sign_name','required'])}}
                                </div>
                                <div class="input-group">
                                    <label>Cargo</label>
                                    {{ Form::text('sign_charge', null, ['class' => 'form-control', 'id'=>'sign_charge','required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="content-divider"></div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-group"> <label>Condiciones comerciales de NOVO NORDISK </label></div>
                                <div class="input-group">
                                    <textarea class="form-control" id="summary-ckeditor-3" name="footer"></textarea>
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
</script>
@endsection
