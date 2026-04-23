@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / Tipos de cliente
        </div>
        <h1>
            Tipos de cliente
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-cubes"></i>
          
                        <h3 class="box-title">Tipos de cliente actuales</h3>
                      </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            <table class="table table-hover">
                                <tbody>
                                    @if (sizeof($clientstype) <= 0) <tr>
                                        <td> No hay tipos registrados</td>
                                        </tr>
                                        @else
                                        @foreach ($clientstype as $key => $clienttype)
                                        <tr>
                                            <td>
                                                {!! Form::model($clienttype, ['route' => ['clientstype.update', $clienttype->id_type], 'method' => 'PUT']) !!}
                                                <div class="row">
                                                    <div class="col-12 col-sm-8">
                                                        <div class="quot-data-box">
                                                            <div class="quot-data-box-content {{ $errors->has('nombre_estadio') ? ' has-error' : '' }}">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-cubes"></i>
                                                                    </div>
                                                                    {{ Form::text('nombre_tipo', $clienttype->type_name, ['class' => 'form-control','autocomplete' => 'off','required']) }}
                                                                </div>
                                                                @if ($errors->has('nombre_tipo'))
                                                                <span class="help-block">
                                                                    <strong>{{ $errors->first('nombre_tipo') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-4">
                                                        <button type="submit" class="btn btn-sm btn-info">
                                                            <i class="fa fa-refresh"></i>
                                                            Actualizar</button>
                                                        <a href="{{ route('clientstype.destroy', ['id' => $clienttype->id_type,]) }}"
                                                            onclick="return confirm('¿Seguro que desea eliminar el tipo {{ $clienttype->type_name }} ?')"
                                                            class="btn btn-sm btn-danger"><i class="fas fa-trash-alt" aria-hidden="true"></i> Eliminar</a>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </td>
                                        </tr>
                                        <tr></tr>
                                        @endforeach
                                        @endif
                                </tbody>
                            </table>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fas fa-plus-square"></i>
          
                        <h3 class="box-title">Agregar nuevo tipo de
                            cliente</h3>
                      </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                                {!! Form::open(['route' => 'clientstype.store']) !!}

                                <div class="form-group">
                                    <label for="inputName">Tipo de cliente*</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-cubes"></i>
                                        </div>
                                        {!! Form::text('nombre_tipo', null, ['class' => 'form-control'.($errors->has('nombre_tipo') ? 'is-invalid':''),'placeholder' => 'Escriba el tipo']) !!}
                                        @error('nombre_tipo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    {!! Form::submit("Crear tipo", ['class'=>'btn btn-primary float-right']) !!}
                                </div>
                                {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.col -->


</div>


@endsection

@section('pagescript')

@endsection