@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / Conceptos de descuento
        </div>
        <h1>
            Conceptos de descuento
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-cubes"></i>
                        <h3 class="box-title">Conceptos actuales</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="datatable1" class="table table-striped table-hover">
                            <tbody>
                                @if (sizeof($concepts) <= 0) <tr>
                                    <td > No hay tipos registrados</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">Concepto</div>
                                                <div class="col-12 col-sm-3">Concepto SAP</div>
                                                <div class="col-12 col-sm-1">Descuento</div>
                                                <div class="col-12 col-sm-2">Agrupar</div>
                                                <div class="col-12 col-sm-3">Acciones</div>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach ($concepts as $key => $concept)
                                    <tr>
                                        <td >

                                            {!! Form::model($concept, ['route' => ['concepts.update',
                                            $concept->id_negotiation_concepts], 'method' => 'PUT']) !!}
                                            <div class="row">
                                                <div class="col-12 col-sm-3 no-padding">
                                                    <div class="quot-data-box">
                                                        <div
                                                            class="quot-data-box-content {{ $errors->has('name_concept') ? ' has-error' : '' }}">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-cubes"></i>
                                                                </div>
                                                                {{ Form::text('name_concept', $concept->name_concept, ['class' => 'form-control','autocomplete' => 'off','required']) }}
                                                            </div>
                                                            @if ($errors->has('name_concept'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('name_concept') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="quot-data-box">
                                                        <div
                                                            class="quot-data-box-content {{ $errors->has('sap_concept') ? ' has-error' : '' }}">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-file-csv"></i>
                                                                </div>
                                                                {{ Form::text('sap_concept', $concept->sap_concept, ['class' => 'form-control','autocomplete' => 'off','required']) }}
                                                            </div>
                                                            @if ($errors->has('sap_concept'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('sap_concept') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-1 no-padding">
                                                    <div class="quot-data-box">
                                                        <div
                                                            class="quot-data-box-content {{ $errors->has('concept_percentage') ? ' has-error' : '' }}">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fas fa-percentage"></i>
                                                                </div>
                                                                {{ Form::text('concept_percentage', $concept->concept_percentage, ['class' => 'form-control','autocomplete' => 'off']) }}
                                                            </div>
                                                            @if ($errors->has('concept_percentage'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('concept_percentage') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-2">
                                                    <div class="quot-data-box">
                                                        <div class="quot-data-box-content {{ $errors->has('concept_percentage') ? ' has-error' : '' }}">
                                                            <div class="input-group">
                                                                <span>
                                                                    {{ Form::checkbox('compress', 1,$concept->concept_compress, ['id' => 'compress']) }}
                                                                    <label for=""> Agrupar en notas</label>
                                                                </span>
                                                            </div>
                                                            @if ($errors->has('concept_compress'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('concept_compress') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <button type="submit" class="btn btn-sm btn-info">
                                                        <i class="fa fa-refresh"></i>
                                                        Actualizar</button>
                                                    <a href="{{ route('concepts.destroy', ['id' => $concept->id_negotiation_concepts,]) }}"
                                                        onclick="return confirm('¿Seguro que desea eliminar el tipo {{  $concept->name_concept}} ?')"
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

                        <h3 class="box-title">Agregar nuevo concepto</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        {!! Form::open(['route' => 'concepts.store']) !!}

                        <div class="form-group">
                            <label for="inputName">Nombre del concepto*</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-cubes"></i>
                                </div>
                                {!! Form::text('name_concept', null, ['class' =>
                                'form-control'.($errors->has('name_concept') ? 'is-invalid':''),'placeholder' =>
                                'Escriba el nombre']) !!}
                                @error('name_concept')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName">Concepto SAP*</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-file-csv"></i>
                                </div>
                                {!! Form::text('sap_concept', null, ['class' =>
                                'form-control'.($errors->has('sap_concept') ? 'is-invalid':''),'placeholder' =>
                                'Escriba el concepto sap']) !!}
                                @error('sap_concept')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputName">Porcentaje de descuento*</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                {!! Form::text('concept_percentage', null, ['class' =>
                                'form-control'.($errors->has('concept_percentage') ? 'is-invalid':''),'placeholder' =>
                                'Escriba el porcentaje']) !!}
                                @error('concept_percentage')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName">Agrupar en notas</label>
                            <div class="input-group">
                                <span>
                                    {{ Form::checkbox('compress',1, null, ['id' => 'compress']) }}
                                    Activo
                                </span>
                            </div>
                            @if ($errors->has('concept_compress'))
                            <span class="help-block">
                                <strong>{{ $errors->first('concept_compress') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div>
                            {!! Form::submit("Crear concepto", ['class'=>'btn btn-primary float-right']) !!}
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
