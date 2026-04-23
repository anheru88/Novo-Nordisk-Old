@extends('admin.layout')
@section('content')
<div class="content-wrapper" id="app">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / Simulador ARP
            </div>
            <h1>
                Simulador ARP
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    {!! Form::open(['route' => ['arp.import'], 'method' => 'POST', 'files' => 'true']) !!}
                    <section class="row">
                        <div class="container-fixed">
                            <div class="col-xs-12 col-sm-10">
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
                                                    {{ Form::button('<i class="fas fa-cloud-download-alt"></i> Importar ventas', ['type' => 'submit', 'class'=> 'btn btn-info pull-left' ]) }}
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
                                    <h3 class="box-title"><i class="fas fa-calculator"></i> Simulaciones realizadas</h3>
                                </div>
                                <div class="box-body">
                                    <div class="overflow">
                                        <table id="datatable1" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    {{-- <th>#</th> --}}
                                                    <th>Simulación</th>
                                                    <th width="50%">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($simulations as $key => $simulation)
                                                <tr>
                                                    {{-- <td>{{ $key + 1 }}</td> --}}
                                                    <td>{{ $simulation->simulation_name }}</td>
                                                    <td>
                                                        <div class="col-12 btns-table">
                                                            <div class="form-group">
                                                            {!! Form::open(['route' => ['arp.xls'], 'method' => 'POST', 'files' => 'false']) !!}
                                                                <label>Seleccione un tipo de ARP</label>
                                                                {!! Form::select('arp',$arp, null,['class'=> 'clientes-select
                                                                form-control focus filter-table-textarea', 'placeholder' => 'Seleccione',
                                                                'required']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                            <div class="btn-action">
                                                                {!! Form::hidden('id_simulacion', $simulation->id) !!}
                                                                {{ Form::button('<i class="fa fa-file-excel"></i> Descargar simulación', ['type' => 'submit', 'class'=> 'btn btn-success pull-left' ])}}
                                                            </div>
                                                            {!! Form::close() !!}
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="btn-action">
                                                                <a href="{{ route('simulator.destroy', $simulation->id) }}"
                                                                    class="btn btn-danger pull-right"
                                                                    onclick="return confirm('¿Seguro que desea eliminar la nota {{  $simulation->simulation_name }} ?')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                    ELIMINAR
                                                                </a>
                                                            </div>
                                                            </div>
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
@endsection

