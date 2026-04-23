@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="content" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Home</a> / <a href="{{  route('clients.index') }}">Clientes</a> / Crear
                cliente
            </div>
            <h1>
                Crear nuevo cliente
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-info quot">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Error!</strong> Revise los campos obligatorios.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <!-- /.box-header -->
                {!! Form::open(['action' => 'ClientsController@store', 'name' => 'cliente', 'id' => 'form_cliente',
                'files'=>'true', 'method' => 'POST']) !!}
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="content-divider"></div>
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 col-sm-12 col-md-6 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Nombre del cliente</div>
                                    <div class="quot-data-box-content">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="ion ion-ios-person"></i>
                                            </div>
                                            {{ Form::text('client_name', null, ['class' => 'form-control', 'id'=>'client','required']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title"> NIT</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::number('client_nit', null, ['class' => 'form-control', 'id'=>'client_quot','required', 'v-on:keypress' => 'numbervalidator()']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Tipo de cliente</div>
                                    <div class="quot-data-box-content">
                                        <select id="id_client_type" name="id_client_type"
                                            class="form-control focus filter-table-textarea">
                                            <option value=""> - Seleccione - </option>
                                            @foreach($client_types as $client_type)

                                            <option value="{{ $client_type->id_type }}">
                                                {{ $client_type->type_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 no-padding">
                                <!--<div class="col-xs-6 col-sm-12 col-md-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Nombre en cotización</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('client_quote_name', null, array('required'), ['class' => 'form-control', 'id'=>'client_quot']) }}
                                        </div>
                                    </div>
                                </div>
                                -->

                                <div class="col-xs-12 col-sm-4 col-md-4 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Departamento</div>
                                        <div class="quot-data-box-content">
                                            <select id="id_department" name="id_department"
                                                class="form-control focus filter-table-textarea" @change="changeCity()"
                                                v-model="department" required>
                                                <option value=""> - Departamento - </option>
                                                @foreach($departments as $department)
                                                <option value="{{ $department->id_locations }}">
                                                    {{ $department->loc_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Ciudad</div>
                                        <div class="quot-data-box-content">
                                            <select id="id_city" name="id_city"
                                                class="form-control focus filter-table-textarea" v-model="city"
                                                required>
                                                <option :value="undefined"> - Ciudad - </option>
                                                <option v-for="city in cities" :value="city.id_locations">
                                                    @{{city.loc_name}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Canal</div>
                                        <div class="quot-data-box-content">
                                            <select id="id_client_channel" name="id_client_channel"
                                                class="form-control focus filter-table-textarea">
                                                <option value=""> - Seleccione - </option>
                                                @foreach($dist_channels as $dist_channel)

                                                <option value="{{ $dist_channel->id_channel }}">
                                                    {{ $dist_channel->channel_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Seccion del Financiero -->
                            @can('financiero')
                            <div class="col-xs-12 col-sm-12 no-padding">
                                <div class="col-xs-12 col-sm-3 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title"> Código SAP</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('client_sap_code', null, ['class' => 'form-control', 'id'=>'client_sap_code'])}}
                                            {{-- <multiselect-componet></multiselect-componet> --}}
                                            {{-- {!! Form::select('client_sap_code[]', $sap_codes, null,['class'=>
                                            '', 'id' => 'client_sap_code', 'data-placeholder' => 'true','required', 'multiple']) !!} --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Cupo</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                {{ Form::text('client_credit', null, ['class' => 'form-control', 'id'=>'client']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Forma de pago</div>
                                        <div class="quot-data-box-content">
                                            <select id="id_payterm" name="id_payterm"
                                                class="form-control focus filter-table-textarea" @change="getPayForm()">
                                                <option value="">Seleccione</option>
                                                @foreach($forma_pagos as $forma_pago)
                                                <option value="{{ $forma_pago->id_payterms }}">
                                                    {{ $forma_pago->payterm_name }}</option>
                                                @endforeach
                                            </select>
                                            {{ Form::hidden('time_discount', null, ['class' => 'form-control', 'id'=>'time_discount','v-model'=>'timeDiscount']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Estado del cliente</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::select('active',array('1' => 'Activo','0' => 'Inactivo'),null,['class'=>'form-control focus filter-table-textarea', 'placeholder' => 'Selecione un estado']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            <!-- Seccion del Financiero -->
                        </div>
                        <div class="content-divider"></div>
                        <div class="row quot-first-data">
                            <div class="col-xs-12 col-sm-12 no-padding">
                                <div class="col-xs-12 col-sm-12 col-md-4 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Nombre de contacto</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                {{ Form::text('client_contact', null, ['class' => 'form-control', 'id'=>'n_contacto','required'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Cargo actual</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fas fa-address-card"></i>
                                                </div>
                                                {{ Form::text('client_position', null, ['class' => 'form-control', 'id'=>'cargo'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Dirección</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-pin"></i>
                                                </div>
                                                {{ Form::text('client_address', null, ['class' => 'form-control', 'id'=>'address'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Número de teléfono</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                {{ Form::text('client_phone', null, ['class' => 'form-control', 'id'=>'phone_number'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-5 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Email de contacto principal</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </div>
                                                {{ Form::text('client_email', null, ['class' => 'form-control', 'id'=>'email'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-5 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Otros emails de contacto</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </div>
                                                {{ Form::text('client_email_second', null, ['class' => 'form-control', 'id'=>'client_email_second'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-divider"></div>
                                <div class="col-xs-12 col-sm-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">CAM ASIGNADO AL CLIENTE</div>
                                        <div class="quot-data-box-content">
                                            <select id="id_diab_contact"
                                                class="form-control focus filter-table-textarea" name="id_diab_contact"
                                                @change="getUsersDiab()" v-model="diabetesUser" required>
                                                <option value="">Seleccionar...</option>
                                                @if (sizeof($usuarios) > 0)
                                                @foreach ($usuarios as $key => $usuario)
                                                @if ($usuario['name'] == "")
                                                <div class="alert alert-warning">
                                                    <strong>¡Advertencia!</strong> No hay Contactos Disponibles.
                                                </div>
                                                @else
                                                <option value="{{ $usuario['id'] }}">{{ $usuario['name'] }}</option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-icon"><i class="ion ion-ios-person"></i></div>
                                        <div class="quot-data-box-title">DATOS DEL CAM</div>
                                        <div class="quot-data-box-content-small contact-gray-box">
                                            <div class="info">
                                                <div v-for="user in usersDiab" id="descripcion_user"
                                                    class="descripcion_user">
                                                    <div class="span"><strong>Nombre: </strong> @{{ user.name }}</div>
                                                    <div class="span"><strong>Email: </strong> @{{ user.email }}</div>
                                                    <div class="span"><strong>Tel: </strong> @{{ user.phone }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content-divider"></div>
                        <div class="row quot-first-data">
                            <div class="quot-number">
                                <h3><i class="ion ion-compose"></i> Adjuntar documentos</h3>
                            </div>
                            <div class="col-xs-12 col-sm-12 no-padding">
                                <div class="col-sm-12 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Seleccione los documentos que desea adjuntar al
                                            repositorio del cliente</div>
                                        <div class="quot-data-box-content">
                                            <div class="file-loading">
                                                {!! Form::file ('docs[]', ['class'=>'file', 'type'=>'file',
                                                'id'=>'file-1' ,'multiple']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
                <div class="content-divider"></div>
                <div class="quot-send-btn">
                    <input type="submit" value="Crear cliente" class="btn btn-novo-big">
                </div>
                {!! Form::close() !!}
        </section>

    </div>
</div>
<!-- /.content -->
@endsection

@section('pagescript')
<script src="{{ asset('js/api.js') }}"></script>
{{-- <Script>
    $( document ).ready(function() {
        $("#form_cliente").keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        $('#client_sap_code').tagsinput({
            trimValue: true,
            confirmKeys: [13, 44, 32],
            focusClass: 'my-focus-class'
        });
        $('.bootstrap-tagsinput input').on('focus', function() {
            $(this).closest('.bootstrap-tagsinput').addClass('has-focus');
        }).on('blur', function() {
            $(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
        });
    });
</Script> --}}
<script>
    $('#file-1').fileinput({
        allowedFileExtensions: ['jpg', 'png', 'gif','doc','pdf','xls','docx','xlsx'],
        browseClass: "btn btn-primary btn-block",
        browseOnZoneClick: true,
        maxFileSize: 4000,
        maxFileCount: 10,
        showUpload: false,
        showRemove: false,
        showCaption: false,
        showDrag:true,
        showBrowse: false,
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });

</script>
@endsection