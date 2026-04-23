@extends('admin.layout') 
@section('content')
<div class="content-wrapper">
        @include('admin.layouts.breadcrumbs')
<section class="content-header">
    <div class="bread-crumb">
        <a href="#">Home</a> / Usuarios
    </div>
    <h1>
        Administrador de usuarios
    </h1>
    <div class="tools-header">
        @can('users.create')
        <div class="tools-menu-btn granite-text">
            <div class="tools-menu-btn-icon"><i class="fas fa-plus-circle"></i></div>
            <div class="tools-menu-btn-text"><a href="{{ route('users.create') }}">Crear nuevo usuario</a></div>
        </div>
        @endcan
    </div>
</section>
<!-- Main content -->
<section class="content">

    <div class="box box-info">
        <!-- /.box-header -->
        <div class="box-body">
            <table id="datatable_full_user" data-toggle="table" data-pagination="true" data-search="true" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th data-sortable="true">#</th>
                        <th data-sortable="true">Usuario</th>
                        <th data-sortable="true">Nickname</th>
                        <th data-sortable="true">Email</th>
                        <th data-sortable="true">Fecha de creación</th>
                        <th data-sortable="true">Tipo de usuario</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key=>$user)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td class="upperc">{{ $user->name }}</td>
                        <td class="upperc">{{ $user->nickname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td class="upperc"> 
                            @foreach($user->roles as $rol)
                                {{$rol->name }}
                                @if ($rol->id == 2)
                                    @if ($user->is_authorizer == 1)
                                    - Nivel {{ $user->authlevel }}
                                    @endif
                                @endif
                                |
                            @endforeach
                        </td>
                        <td>
                            @can('users.edit')
                                <a href="{{route('users.edit', $user->id)}}" class="btn btn-xs btn-warning"><i class="fas fa-pen"></i></a>
                                @endcan
                                @can('users.delete')
                                @if(Auth::user()->id != $user->id)
                                <a href="{{ route('users.destroy',$user->id) }}" onclick="return confirm('¿Seguro que desea eliminar el usuario {{ $user->name }} ?')" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></a>
                                @endif
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
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
@section('pagescript')
@endsection