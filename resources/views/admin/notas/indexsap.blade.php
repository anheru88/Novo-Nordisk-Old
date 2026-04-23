@extends('admin.layout')
@section('content')
<div class="content-wrapper" id="app">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / Generación de Archivo Plano SAP
            </div>
            <h1>
                Generación de Archivo Plano SAP
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                {!! Form::open(['route' => ['sapnotes.import'], 'method' => 'POST', 'files' => 'true']) !!}
<section class="row">
    <div class="container-fixed">
        <div class="col-xs-12 col-sm-10">
            <div class="form-data-box">
                <div class="form-data-box-title">
                    Seleccione el archivo de Notas Crédito
                </div>
                <div class="col-xs-12 col-sm-4 no-padding-left">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="file-loading">
                                {!! Form::file('doc', ['class' => 'file', 'type' => 'file', 'id' => 'file-1', 'data-show-preview' => 'false']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 no-padding-left">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="btn-report">
                                {{ Form::button('<i class="fas fa-cloud-download-alt"></i> Importar Notas', ['type' => 'submit', 'class' => 'btn btn-info pull-left']) }}
                            </div>
                        </div>        
                    </div>
                </div>
                <div class="col-xs-10 col-sm-4  mt-2 no-padding-left">
                    <div class="form-group">
                        {!! Form::label('hoja_de_credito', 'Hoja de Crédito') !!}
                        {{ Form::select('hoja_de_credito', 
                         ['NC ESCALAS' => 'NC ESCALAS', 'NC INFORMACIÓN' => 'NC INFORMACIÓN', 'NC PRESENTACIÓN' => 'NC PRESENTACIÓN', 
                        'NC PRESENTACIÓN PACK X 3' => 'NC PRESENTACIÓN PACK X 3', 'NC COMERCIAL' => 'NC COMERCIAL', 
                         'NC CONVENIO' => 'NC CONVENIO', 'NC CODIFICACIÓN' => 'NC CODIFICACIÓN', 'NC PAGO' => 'NC PAGO', 
                         'NC PROVISIÓN' => 'NC PROVISIÓN', 'NC CORTA EXPIRA' => 'NC CORTA EXPIRA', 
                        'NC NEGOCIACIÓN ESPECIAL' => 'NC NEGOCIACIÓN ESPECIAL', 'NC ADHERENCIA' => 'NC ADHERENCIA', 
                        'NC LOGISTICA' => 'NC LOGISTICA', 'NC LANZAMIENTO' => 'NC LANZAMIENTO', 'NC CAPITA' => 'NC CAPITA'],
                        null,['class'=> ' clientes-select form-control
                        focus filter-table-textarea', 'placeholder' => 'Seleccione', 'required' => 'required', 'id' => 'hoja_de_credito']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{!! Form::close() !!}

                    <div class="row">
                        <section class="col-xs-12 col-sm-12 connectedSortable">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"> <i class="fa fa-search"></i>Notas Crédito Actuales</h3>
                                </div>
                                <div class="box-body">
                                    <div class="overflow">
                                        <table id="datatable1" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    {{-- <th>#</th> --}}
                                                    <th>Nota</th>
                                                    <th width="45%">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($crenotes as $key =>$note)
                                                <tr>
                                                    {{-- <td>{{ $key + 1 }}</td> --}}
                                                    <td>{{ $note->doc_name }}</td>
                                                    <td>
                                                        <div class="col-4 col-md-4">
                                                            {!! Form::open(['route' => ['sapnotes.csv'], 'method' => 'POST', 'files' => 'false']) !!}
                                                            {!! Form::hidden('id_crenote', $note->id_credit_notes) !!}
                                                            {{ Form::button('<i class="fa fa-file"></i> Archivo SAP', ['type' => 'submit', 'class'=> 'btn btn-success pull-left' ])}}
                                                            {!! Form::close() !!}
                                                        </div>
                                                        <div class="col-4 col-md-4">
                                                            {!! Form::open(['route' => ['sapnotes.xls'], 'method' => 'POST', 'files' => 'false']) !!}
                                                            {!! Form::hidden('id_crenote', $note->id_credit_notes) !!}
                                                            {{ Form::button('<i class="fa fa-file-excel"></i> Archivo Excel', ['type' => 'submit', 'class'=> 'btn btn-success pull-left' ])}}
                                                            {!! Form::close() !!}
                                                        </div>
                                                        <div class="col-4 col-md-4">
                                                            <a href="{{ route('sapnotes.destroy', ['id' => $note->id_credit_notes]) }}"
                                                                class="btn btn-danger pull-right"
                                                                onclick="return confirm('¿Seguro que desea eliminar la nota {{  $note->doc_name }} ?')">
                                                                <i class="fas fa-trash-alt"></i>
                                                                ELIMINAR
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
    </div>
</div>
<!-- /.content -->
@endsection
@section('pagescript')
<script>
    $("#file-1").fileinput({
        allowedFileExtensions: ['xls','xlsx'],
        browseClass: "btn btn-primary btn-block",
        maxFileSize: 4000,
        maxFileCount: 1,
        showUpload: false,
        showRemove: true,
        showCaption: true,
        browseOnZoneClick: false,
        showBrowse: true,
        showDrag:false,
        uploadUrl: '#',
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });
</script>

<script>
    $(document).ready(function() {
        // Obtén el elemento del archivo de ventas y el botón de subir ventas
        var fileInput = $("#file-1");
        var submitButton = $(".btn-report button");

        // Verifica si hay un archivo seleccionado al cargar la página
        if (fileInput.fileinput('getFilesCount') === 0) {
            submitButton.prop('disabled', true); // Deshabilita el botón de subir ventas
        }

        // Agrega un evento al cambio del archivo de ventas
        fileInput.on('fileloaded fileclear', function(event, previewId, index, reader) {
            // Verifica si se ha cargado o eliminado el archivo
            if (fileInput.fileinput('getFilesCount') > 0) {
                submitButton.prop('disabled', false); // Habilita el botón de subir ventas
            } else {
                submitButton.prop('disabled', true); // Deshabilita el botón de subir ventas
            }
        });

        // Agrega un evento al clic en el botón de subir ventas
        submitButton.on('click', function() {
            if (fileInput.fileinput('getFilesCount') === 0) {
                alert("Por favor, carga un archivo antes de subir las ventas.");
                return false; // Evita que se envíe el formulario si no hay archivo
            }
            // Puedes agregar aquí la lógica para enviar el formulario con el archivo seleccionado
        });
    });
</script>

@endsection


