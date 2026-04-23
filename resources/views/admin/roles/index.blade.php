@extends('admin.layout')
@section('content')
<div class="content-wrapper">
@include('admin.layouts.breadcrumbs')
<section class="content-header">
    <div class="bread-crumb">
        <a href="#">Home</a> / Roles
    </div>
    <h1>
        Administrador de roles
    </h1>
    <div class="tools-header">
        @can('roles.create')
        <div class="tools-menu-btn granite-text">
            <div class="tools-menu-btn-icon"><i class="fas fa-plus-circle"></i></div>
            <div class="tools-menu-btn-text"><a href="{{ route('roles.create') }}">Crear nuevo rol</a></div>
        </div>
        @endcan
    </div>
</section>
<!-- Main content -->
<section class="content">
    <div class="col-xs-12 ">
        <div class="row">
    <div class="box box-info">
        <!-- /.box-header -->
        <div class="box-body">
            <table id="datatable_full_role" data-toggle="table" data-pagination="true" data-search="true" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th data-sortable="true">#</th>
                        <th data-sortable="true">Nombre</th>
                        <th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $key=>$role)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $role->name }} <em>({{ $role->description ?: 'Sin descripción' }})</em></td>
                        <td>
                            @can('roles.edit')
                            <button class="btn btn-xs btn-warning" onclick="location.href='{{ route('roles.edit', $role->id) }}'"><i class="fas fa-pen"></i></button>
                            @endcan
                            @can('roles.destroy')
                            @if (auth()->user()->hasRole($role->slug))
                            @else
                            <a href="{{route('roles.destroy',$role->id)}}" onclick="return confirm('¿Seguro que desea eliminar el rol {{ $role->name }} ?')" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></a>
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
    </div>
</div>
</section>
</div>
<!-- /.content -->
@endsection
@section('pagescript')
@endsection
