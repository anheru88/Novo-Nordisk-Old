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
                Configuración de ARP
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <i class="fa fa-cube" aria-hidden="true"></i>

                            <h3 class="box-title">ARP actuales</h3>
                          
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="datatable1" class="table table-striped table-hover">
                                <tbody>
                                    @if (sizeof($arps) <= 0) <tr>
                                        <td > No hay ARP registrados</td>
                                        </tr>
                                        @else
                                        @foreach ($arps as $key => $arp)
                                        <tr>
                                            <td >
                                                <div class="row">
                                                    <div class="col-12 col-sm-8">
                                                        {{$arp->name}}
                                                    </div>
                                                    <div class="col-12 col-sm-4 ">
                                                        <div class="pull-right">
                                                            <a href="{{ route('arp.edit', $arp->id) }}" class="btn btn-sm btn-info">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                                AGREGAR INFORMACIÓN</a>
                                                                <a href="{{ route('arp.destroy', $arp->id) }}"
                                                                    class="btn btn-sm btn-danger pull-right"
                                                                    onclick="return confirm('¿Seguro que desea eliminar la nota {{  $arp->name }} ?')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                    ELIMINAR
                                                                </a>
                                                        </div>

                                                    </div>
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

                            <h3 class="box-title">Crear nuevo ARP</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            {!! Form::open(['route' => 'arp.store']) !!}

                            <div class="form-group">
                                <label for="inputName">Nombre ARP*</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-cubes"></i>
                                    </div>
                                    {!! Form::text('name', old('name'), ['class' => 'form-control'.($errors->has('name') ? 'is-invalid':''),'placeholder' => 'Escriba el nombre del ARP']) !!}
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName">Año de vigencia*</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-cubes"></i>
                                    </div>
                                    {!! Form::number('year', old('year'), ['class' => 'form-control'.($errors->has('year') ? 'is-invalid':''),'placeholder' => 'Escriba el año de vigencia']) !!}
                                    @error('year')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName">STD* ()</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-cubes"></i>
                                    </div>
                                    {!! Form::text('std', old('std'), ['class' => 'form-control'.($errors->has('std') ? 'is-invalid':''),'placeholder' => 'Escriba el STD asignado']) !!}
                                    @error('std')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName">MES* ()</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-cubes"></i>
                                    </div>
                                    <select name="month_avr" id="month_avr" class="form-control">
                                        <option value="1">Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                            </div>


                            <div>
                                {!! Form::submit("Crear ARP", ['class'=>'btn btn-primary float-right']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- /.content -->
@endsection
