@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / Lineas de producto
        </div>
        <h1>
            Lineas de producto
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-cubes"></i>

                        <h3 class="box-title">Lineas actuales</h3>
                      </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            <table data-toggle="table" data-pagination="true" data-search="true" class="table table-striped table-hover">
                                <tbody>
                                    @if (sizeof($lines) <= 0) <tr>
                                        <td data-sortable="true"> No hay tipos registrados</td>
                                        </tr>
                                        @else
                                        @foreach ($lines as $key => $line)
                                        <tr>
                                            <td data-sortable="true">
                                                {!! Form::model($line, ['route' => ['productlines.update', $line->id_line], 'method' => 'PUT']) !!}
                                                <div class="row">
                                                    <div class="col-12 col-sm-8">
                                                        <div class="quot-data-box">
                                                            <div class="quot-data-box-content {{ $errors->has('nombre_estadio') ? ' has-error' : '' }}">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-cubes"></i>
                                                                    </div>
                                                                    {{ Form::text('nombre_linea', $line->line_name, ['class' => 'form-control','autocomplete' => 'off','required']) }}
                                                                </div>
                                                                @if ($errors->has('nombre_linea'))
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
                                                        <a href="{{ route('productlines.destroy', ['id' => $line->id_line,]) }}"
                                                            onclick="return confirm('¿Seguro que desea eliminar el tipo {{ $line->line_name }} ?')"
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

                        <h3 class="box-title">Agregar nuevo tipo de linea</h3>
                      </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                                {!! Form::open(['route' => 'productlines.store']) !!}

                                <div class="form-group">
                                    <label for="inputName">Tipo de linea*</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-cubes"></i>
                                        </div>
                                        {!! Form::text('nombre_linea', null, ['class' => 'form-control'.($errors->has('nombre_linea') ? 'is-invalid':''),'placeholder' => 'Escriba el tipo']) !!}
                                        @error('nombre_linea')
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
