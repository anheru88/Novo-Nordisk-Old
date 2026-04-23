@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="content" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="#">Inicio</a> / <a href="{{ route('clients.index') }}">Clientes </a> / Editar cliente
            </div>
        </section>
        <!-- Main content -->
        {!! Form::open(['route' => ['clients.update',$cliente->id_client], 'method' => 'PUT', 'files' => 'true']) !!}
        <section class="">
            <div class="box box-info quot">
                @if (count($errors) > 0)
                <div class="alert alert-info">
                    {{ $error }}
                </div>
                @endif
                <!-- /.box-header -->

                <div class="box-body">
                    <div class="quot-number">
                        <h1>{{ $cliente->client_name }} </h1> (Modo Edición) <i class="fa fa-edit"></i>
                    </div>
                    <div class="content-divider"></div>
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 col-sm-12 no-padding">
                                <div class="col-xs-12 col-sm-4 col-md-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Nombre del cliente</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('client_name', $cliente->client_name, ['class' => 'form-control', 'id'=>'client_name']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">NIT</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('client_nit', $cliente->client_nit, ['class' => 'form-control', 'id'=>'client_nit', 'v-on:keypress' => 'numbervalidator()']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Canal</div>
                                        <div class="quot-data-box-content">
                                            {!! Form::select('id_client_channel',$dist_channels,
                                            $cliente->id_client_channel,['class'
                                            => 'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione
                                            un uso' ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Tipo de cliente</div>
                                        <div class="quot-data-box-content">
                                            {!! Form::select('id_client_type',$client_types,
                                            $cliente->id_client_type,['class'
                                            => 'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione
                                            un uso',
                                            'required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Departamento</div>
                                        <div class="quot-data-box-content">
                                            {!! Form::select('id_department',$locations,
                                            $cliente->id_department,['class' =>
                                            'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione',
                                            'required', 'v-on:change'=>'changeCity()']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Ciudad</div>
                                        <div class="quot-data-box-content" v-if="department">
                                            <select id="id_city" name="id_city"
                                                class="form-control focus filter-table-textarea" v-model="city"
                                                required>
                                                <option :value="undefined"> - Ciudad - </option>
                                                <option v-for="city in cities" :value="city.id_locations">
                                                    @{{city.loc_name}}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="quot-data-box-content" v-else>
                                            {!! Form::select('id_city',$cities, $cliente->id_city,['class' =>
                                            'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione',
                                            'required']) !!}
                                        </div>

                                    </div>
                                </div>
                                @can('financiero')
                                <div class="col-xs-12 col-sm-4 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title"> Código SAP</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('client_sap_code', $cliente->client_sap_code, ['class' => 'form-control', 'id'=>'client_sap_code', 'required' => true])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Cupo</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                {{ Form::text('client_credit', $cliente->client_credit, ['class' => 'form-control', 'id'=>'client_credit']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Forma de pago</div>
                                        <div class="quot-data-box-content">
                                            {!! Form::select('id_payterm',$forma_pagos, $cliente->id_payterm,['class' =>
                                            'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione',
                                            'required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Estado del cliente</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::select('active',array('1' => 'Activo','0' => 'Inactivo'),$cliente->active,['class'=>'form-control focus filter-table-textarea', 'placeholder' => 'Selecione un estado']) }}
                                        </div>
                                    </div>
                                </div>
                                @endcan
                            </div>
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
                                                {{ Form::text('client_contact', $cliente->client_contact, ['class' => 'form-control', 'id'=>'client_contact','required'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Cargo de contacto</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fas fa-address-card"></i>
                                                </div>
                                                {{ Form::text('client_position', $cliente->client_position, ['class' => 'form-control', 'id'=>'client_position'])}}
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
                                                {{ Form::text('client_address', $cliente->client_address, ['class' => 'form-control', 'id'=>'client_address'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Teléfono</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-phone"></i>
                                                </div>
                                                {{ Form::text('client_phone', $cliente->client_phone, ['class' => 'form-control', 'id'=>'client_phone'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-5 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Email de contacto principal</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </div>
                                                {{ Form::text('client_email', $cliente->client_email, ['class' => 'form-control', 'id'=>'client_email'])}}
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
                                                {{ Form::text('client_email_second', $cliente->client_email_secondary, ['class' => 'form-control', 'id'=>'client_email_second'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-divider"></div>
                                <div class="col-xs-12 col-sm-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-icon"><i class="ion ion-ios-person"></i></div>
                                        <div class="quot-data-box-title">CAM ASIGNADO AL CLIENTE</div>
                                        <div class="quot-data-box-content">
                                            {!! Form::select('id_diab_contact',$usuarios,
                                            $cliente->id_diab_contact,['class' =>
                                            'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione un
                                            uso']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-icon"><i class="ion ion-ios-person"></i></div>
                                        <div class="quot-data-box-title">DATOS DEL CAM</div>
                                        <div class="quot-data-box-content-small contact-gray-box">
                                            <div class="info">
                                                <div id="descripcion_user" class="descripcion_user">
                                                    <div class="span"><strong>Nombre: </strong>
                                                        {{ $contactoDiab->name }}</div>
                                                    <div class="span"><strong>Email: </strong>
                                                        {{ $contactoDiab->email }}</div>
                                                    <div class="span"><strong>Tel: </strong> {{ $contactoDiab->phone }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

        <!-- /.Seccion de documentos-->
        <section class="">
            <div class="box box-info quot collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-calendar"></i> Documentos del cliente</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row quot-first-data">
                        <div class="col-xs-12 col-sm-12 no-padding">
                            <div class="table-responsive">
                                <table id="informe" data-toggle="table" data-pagination="true" data-search="true"
                                    class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">#</th>
                                            <th data-sortable="true">Documento</th>
                                            <th data-sortable="true">Subido el</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($cliente->files->count())
                                        @foreach($cliente->files as $key => $doc)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><a href="{{ url('/').'/uploads/'.$cliente->id_client.'/'.$doc->file_name }}"
                                                    target="_blank"><i class="fas fa-file-alt"></i>
                                                    {{ $doc->file_name }}</a>
                                            </td>
                                            <td><i class="fa fa-calendar"></i>
                                                {{ date('d-m-Y',strtotime($doc->created_at )) }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td>No hay registro</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-xs-12 no-padding">
                            <a href="{{ route('files.edit',['file' => $cliente->id_client]) }}"
                                class="btn btn-secondary"><i class="fas fa-upload"></i> Subir más documentos</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- /.Modal de de descuento-->
        <section class="">
            <div class="box box-info quot collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fas fa-syringe"></i> Condiciones de pago</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row quot-first-data">
                        <div class="col-xs-12 col-sm-12 no-padding">
                            <div class="table-responsive">
                                <table id="informe" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Condición financiera</th>
                                            <th class="text-center">Desc. Condición financiera</th>
                                            <th class="text-center">Desc. Precio</th>
                                            <th>Vigencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($clientProducts->count())
                                        @foreach($clientProducts as $product)
                                        <tr>
                                            <td>{{ $product->prod_name }}</td>
                                            <td>
                                                {{ getPayTermCode($product->id_payterm ) }} -
                                                {{ $product->payterm_name }}
                                            </td>
                                            <td class="text-center">{{ getPaytermPercent($product->id_payterm)}}%</td>
                                            <td class="text-center">{{ $product->pay_discount }}%</td>
                                            <td>
                                                @php
                                                $prodInfo = getProductInfo($product->id_product);
                                                $product_date = date('Y-m-d',strtotime($prodInfo->prod_valid_date_end));
                                                $today = date('Y-m-d');
                                                if($product_date >= $today){
                                                $vigencia = "SI";
                                                }else{
                                                $vigencia = "NO";
                                                }
                                                @endphp
                                                @if ($vigencia == "NO")
                                                <span class="label label-danger">Vencido</span>
                                                @else
                                                <span class="label label-success">Vigente</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td>No hay registro</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- /.Modal de de escala-->
        <section class="">
            <div class="box box-info quot collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fas fa-chart-bar"></i> Escalas de productos</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row quot-first-data">
                        <div class="col-xs-12 col-sm-12 no-padding">
                            <div class="table-responsive">
                                <table id="informe" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th class="text-center">Valor unidad minima</th>
                                            <th class="text-center">Valor presentación comercial</th>
                                            <th class="text-center">Escala</th>
                                            <th>Vigencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($clientProducts->count())
                                        @foreach($clientProducts as $product)
                                        <tr>
                                            <td>{{ $product->prod_name }} {!! Form::hidden('productScale[]',
                                                $product->id_product) !!}</td>
                                            <td class="text-center">
                                                ${{ number_format( $product->prod_cost,0, ",", ".")  }}</td>
                                            <td class="text-center">
                                                ${{ number_format( $product->price_uminima,0, ",", ".")}}</td>
                                            <td class="text-center">
                                                {!! Form::select('escalas[]', getProductScales($product->id_product),
                                                getProductxClientScales($product->id_product,$cliente->id_client)
                                                ,['class' =>
                                                'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione
                                                una escala']) !!}
                                            </td>
                                            <td>
                                                @php
                                                $prodInfo = getProductInfo($product->id_product);
                                                $product_date = date('Y-m-d',strtotime($prodInfo->prod_valid_date_end));
                                                $today = date('Y-m-d');
                                                if($product_date >= $today){
                                                $vigencia = "SI";
                                                }else{
                                                $vigencia = "NO";
                                                }
                                                @endphp
                                                @if ($vigencia == "NO")
                                                <span class="label label-danger">Vencido</span>
                                                @else
                                                <span class="label label-success">Vigente</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td>No hay registro</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="quot-send-btn">
            <input type="submit" value="Guardar" class="btn btn-novo-big">
        </div>
        {!! Form::close() !!}

        <div class="modal fade" id="modal-edit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title"><i class="ion ion-ios-search-strong"></i> Cambiar condición de pago</h3>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['url' => '/finder', 'method' => 'POST']) !!}
                        <div>
                            <table class="table no-margin table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Condicion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="pages/examples/invoice.html">GLUCAGEN® VIAL</a></td>
                                        <td>F090 - 90 Dias Neto</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">Seleccione la nueva condición de pago</div>
                        <div class="form-group">
                            {!! Form::select('marca_vehiculo_id[]',['L' => 'Cooperativa de Organismos de
                            Salud COOSBOY', 'S' => 'Cooperativa
                            de Organismos de Salud COOSBOY'],null,['class' => 'form-control focus
                            filter-table-textarea',
                            'placeholder'
                            => '----', 'id' => 'marca_vehiculo_id']) !!}</div>

                        {!! Form::close() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-novo pull-right">Cambiar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>
</div>
<!-- /.content -->
@endsection

@section('pagescript')
<script src="{{ asset('js/api.js') }}"></script>
{{-- <Script>
    $( document ).ready(function() {
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
@endsection
