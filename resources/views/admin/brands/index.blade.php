@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / Marcas
        </div>
        <h1>
            Marcas
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-cubes"></i>

                        <h3 class="box-title">Marcas actuales</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="datatable1" class="table table-striped table-hover">
                            <tbody>
                                @if (sizeof($brands) <= 0) <tr>
                                    <td > No hay marcas registradas</td>
                                    </tr>
                                    @else
                                    @foreach ($brands as $key => $brand)
                                    <tr>
                                        <td >
                                            {!! Form::model($brand, ['route' => ['brands.update', $brand->id_brand], 'method' => 'PUT']) !!}
                                            <div class="row">
                                                <div class="col-12 col-sm-8">
                                                    <div class="quot-data-box">
                                                        <div
                                                            class="quot-data-box-content {{ $errors->has('nombre_marca') ? ' has-error' : '' }}">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-cubes"></i>
                                                                </div>
                                                                {{ Form::text('nombre_marca', $brand->brand_name, ['class' => 'form-control','autocomplete' => 'off','required']) }}
                                                            </div>
                                                            @if ($errors->has('nombre_marca'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('nombre_marca') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <button type="submit" class="btn btn-sm btn-info">
                                                        <i class="fa fa-refresh"></i>
                                                        Actualizar</button>
                                                    <a href="{{ route('brands.destroy', ['id' => $brand->id_brand,]) }}"
                                                        onclick="return confirm('¿Seguro que desea eliminar la marca {{ $brand->brand_name }} ?')"
                                                        class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"
                                                            aria-hidden="true"></i> Eliminar</a>
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

                        <h3 class="box-title">Agregar nueva marca</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        {!! Form::open(['route' => 'brands.store']) !!}

                        <div class="form-group">
                            <label for="inputName">Marca*</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-cubes"></i>
                                </div>
                                {!! Form::text('nombre_marca', null, ['class' => 'form-control'.($errors->has('nombre_marca') ? 'is-invalid':''),'placeholder' => 'Escriba la marca']) !!}
                                @error('nombre_marca')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            {!! Form::submit("Crear marca", ['class'=>'btn btn-primary float-right']) !!}
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
