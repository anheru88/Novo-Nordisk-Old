@extends('admin.layout')
@section('content')
<div class="content-wrapper" id="app">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / Generación de notas
            </div>
            <h1>
                Generación de Notas
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    {!! Form::open(['route' => ['notas.masive'], 'method' => 'POST', 'files' => 'true']) !!}
                    <section class="row">
                        <div class="container-fixed">
                            <div class="col-xs-12">
                                <div class="form-data-box">
                                    <div class="form-data-box-title">
                                        Seleccione el archivo de ventas
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding-left">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="file-loading">
                                                    {!! Form::file ('doc', ['class'=>'file',
                                                    'type'=>'file','id'=>'file-1','data-show-preview'=>'false']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 no-padding-left">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="btn-report">
                                                    {{ Form::button('<i class="fas fa-cloud-upload-alt"></i> Subir ventas', ['type' => 'submit', 'class'=> 'btn btn-info pull-left' ]) }}
                                                </div>
                                            </div>
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
                                    <h3 class="box-title"> <i class="fa fa-search"></i>Notas generadas</h3>
                                </div>
                                <div class="box-body">
                                    <div class="overflow">
                                        <form class="{{ route('notas') }}" method="GET">
                                            <div class="form-group mb-2">
                                                <div class="container-fluid no-padding">
                                                        <div class="col-md-10 no-padding">
                                                            <div class="search-table">
                                                                {!! Form::select('quantity', [
                                                                    '10' => '10',
                                                                    '20' => '20',
                                                                    '50' => '50',
                                                                    '100' => '100',
                                                                    ], $quantity, ['class'=>"form-control select-min", 'onchange'=>'this.form.submit()'])
                                                                !!}
                                                                <input type="text" class="form-control" id="filter" name="filter"
                                                                    placeholder="Buscar" value="{{ $filter }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-default mb-2">Buscar</button>
                                                        </div>
                                                    </div>
                                            </div>
                                        </form>
                                        <table id="datatable1" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    {{-- <th>#</th> --}}
                                                    <th>Nota</th>
                                                    <th width="23%">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sales as $key =>$sale)
                                                <tr>
                                                    {{-- <td>{{ $key + 1 }}</td> --}}
                                                    <td>{{ $sale->doc_name }}</td>
                                                    <td>
                                                        <div class="col-6 mr-1">
                                                            {!! Form::open(['route' => ['notas.download'], 'method' =>
                                                            'POST', 'files' => 'false']) !!}
                                                            {!! Form::hidden('id_sales', $sale->id_sales) !!}
                                                            {{ Form::button('<i class="fa fa-file-excel-o"></i> Generar notas', ['type' => 'submit', 'class'=> 'btn btn-success pull-left' ])}}
                                                            {!! Form::close() !!}
                                                        </div>
                                                        <div class="col-6 ml-1">
                                                            <a href="{{ route('notas.destroy', ['id' => $sale->id_sales]) }}"
                                                                class="btn btn-danger pull-right"
                                                                onclick="return confirm('¿Seguro que desea eliminar la nota {{  $sale->doc_name }} ?')">
                                                                <i class="fas fa-trash-alt"></i>
                                                                ELIMINAR NOTAS
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{$sales->appends(request()->all())->links()}}
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
<script src="{{ asset('js/reports.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script src="{{ asset('js/api.js') }}"></script>
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
    });
</script>

@endsection
