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
            Formas de pago
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-cubes"></i>
          
                        <h3 class="box-title">Formas de pago actuales</h3>
                      </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            <table class="table table-hover">
                                <tbody>
                                    @if (sizeof($paymethods) <= 0) <tr>
                                        <td> No hay tipos registrados</td>
                                        </tr>
                                        @else
                                        @foreach ($paymethods as $key => $paymethod)
                                        <tr>
                                            <td>
                                                {!! Form::model($paymethod, ['route' => ['paymethods.update', $paymethod->id_payterms], 'method' => 'PUT']) !!}
                                                <div class="row">
                                                    <div class="col-12 col-sm-6">
                                                        <div class="quot-data-box">
                                                            <div class="quot-data-box-content {{ $errors->has('payterm_name') ? ' has-error' : '' }}">
                                                                <label for="inputName">Forma de pago*</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fas fa-edit"></i>
                                                                    </div>
                                                                    {{ Form::text('payterm_name', $paymethod->payterm_name, ['class' => 'form-control','autocomplete' => 'off','required']) }}
                                                                </div>
                                                                @if ($errors->has('payterm_name'))
                                                                <span class="help-block">
                                                                    <strong>{{ $errors->first('payterm_name') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-1 no-padding">
                                                        <div class="quot-data-box">
                                                            <div class="quot-data-box-content {{ $errors->has('payterm_percent') ? ' has-error' : '' }}">
                                                                <label for="inputName">Porcentaje*</label>
                                                                <div class="input-group">
                                                                    {{ Form::text('payterm_percent', $paymethod->payterm_percent, ['class' => 'form-control','autocomplete' => 'off','required']) }}
                                                                    <div class="input-group-addon">
                                                                        <i class="fas fa-percentage"></i>
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has('payterm_percent'))
                                                                <span class="help-block">
                                                                    <strong>{{ $errors->first('payterm_percent') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-2 ">
                                                        <div class="quot-data-box">
                                                            <div class="quot-data-box-content {{ $errors->has('payterm_code') ? ' has-error' : '' }}">
                                                                <label for="inputName">Código*</label>
                                                                <div class="input-group">
                                                                    {{ Form::text('payterm_code', $paymethod->payterm_code, ['class' => 'form-control','autocomplete' => 'off','required']) }}
                                                                </div>
                                                                @if ($errors->has('payterm_code'))
                                                                <span class="help-block">
                                                                    <strong>{{ $errors->first('payterm_code') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-3 no-padding">
                                                        <div class="quot-data-box">
                                                            <div class="quot-data-box-content">
                                                            <label for="inputName">&nbsp;</label>
                                                            <div class="input-group">
                                                            <button type="submit" class="btn btn-sm btn-info">
                                                                <i class="fa fa-refresh"></i> Actualizar
                                                            </button>
                                                            <a href="{{ route('paymethods.destroy', ['id' => $paymethod->id_payterms,]) }}"
                                                                onclick="return confirm('¿Seguro que desea eliminar el tipo {{ $paymethod->payterm_name }} ?')"
                                                                class="btn btn-sm btn-danger"><i class="fas fa-trash-alt" aria-hidden="true"></i> Eliminar</a>
                                                            </div>
                                                            </div>
                                                        </div>
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
          
                        <h3 class="box-title">Agregar nueva forma de pago</h3>
                      </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {!! Form::open(['route' => 'paymethods.store']) !!}
                            <div class="form-group">
                                <label for="inputName">Forma de pago*</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    {!! Form::text('payterm_name', null, ['class' => 'form-control'.($errors->has('payterm_name') ? 'is-invalid':''),'placeholder' => 'Escriba la nueva forma']) !!}
                                        @error('payterm_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName">Porcentaje*</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fas fa-percentage"></i>
                                    </div>
                                    {!! Form::text('payterm_percent', null, ['class' => 'form-control'.($errors->has('payterm_percent') ? 'is-invalid':''),'placeholder' => 'Escriba el porcentaje']) !!}
                                        @error('payterm_percent')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName">Código*</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-cubes"></i>
                                    </div>
                                    {!! Form::text('payterm_code', null, ['class' => 'form-control'.($errors->has('payterm_code') ? 'is-invalid':''),'placeholder' => 'Escriba el código']) !!}
                                        @error('payterm_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                {!! Form::submit("Crear nueva forma", ['class'=>'btn btn-primary float-right']) !!}
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