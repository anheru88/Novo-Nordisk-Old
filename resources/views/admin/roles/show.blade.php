@extends('admin.layout') 
@section('content')
<div class="content-wrapper">
        @include('admin.layouts.breadcrumbs')
<section class="content-header">
    <div class="bread-crumb">
        <a href="/">Inicio</a> / <a href="{{ route('roles.index') }}">Administrador de roles</a> / {{ $role->name }}
    </div>
    <div class="tools-header">
        <div class="tools-menu-btn">
            <div class="tools-menu-btn-icon"></div>
        </div>
        <div class="tools-menu-btn pull-right lavared-bg white-text">
            <div class="tools-menu-btn-icon"><i class="fa fa-trash-o"></i></div>
            <div class="tools-menu-btn-text" data-toggle="modal" data-target="#modal-default"> Eliminar</div>
        </div>
        <div class="tools-menu-btn pull-right darkblue-bg white-text">
            <div class="tools-menu-btn-icon"><i class="fa fa-edit"></i></div>
            <div class="tools-menu-btn-text" data-toggle="modal"><a href="1/edit">Modificar</a></div>
        </div>

    </div>
</section>
<!-- Main content -->
<section class="content">
    <div class="col-sm-6 no-padding">
        <div class="box box-info quot">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="quot-number">
                    <h1>{{ $role->name }}</h1>
                </div>
                <div class="content-divider"></div>
                <div class="container-fixed">
                    <div class="row quot-first-data">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Descripcion del role</div>
                            <div class="quot-data-box-content">{{ $role->description }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>


<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="ion ion-ios-search-strong"></i> Buscador de cotizaciones</h3>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => '/finder', 'method' => 'POST']) !!}
                <div class="form-group">Seleccione el año</div>
                <div class="form-group">{!! Form::select('marca_vehiculo_id[]',['L' => '2018', 'S' => '2019'],null,['class' => 'form-control focus
                    filter-table-textarea', 'placeholder' => '----', 'id' => 'marca_vehiculo_id']) !!}</div>
                <div class="form-group">Seleccione un cliente de la lista</div>
                <div class="form-group">{!! Form::select('marca_vehiculo_id[]',['L' => 'AJF FARMA SAS', 'S' => 'Cooperativa de Organismos de Salud
                    COOSBOY'],null,['class' => 'form-control focus filter-table-textarea', 'placeholder' => '----', 'id'
                    => 'marca_vehiculo_id']) !!}</div>

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-novo pull-right">Buscar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
</div>
<!-- /.modal -->
<!-- /.content -->
@endsection