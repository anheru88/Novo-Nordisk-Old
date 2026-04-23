@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header">
        <div class="bread-crumb">
            <a href="/">Inicio</a> / <a href="{{ route('products.index') }}">Productos </a> / {{ $producto->prod_name }}
        </div>
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
            @if(Session::has('success'))
            <div class="alert alert-info">
                {{Session::get('success')}}
            </div>
            @endif
            <!-- /.box-header -->
            {!! Form::open(['action' => ['ProductsController@update', $producto->id_product], 'method' => 'PUT', 'id' =>
            'form_product_edit', 'files'
            => 'true']) !!}
            <div class="box-body">
                <div class="quot-title">
                    <div class="row">
                        <div class="col-xs-12">
                            <h1>{{ $producto->prod_name }}</h1> (Modo Edición) <i class="fa fa-edit"></i>
                        </div>
                    </div>
                </div>
                <div class="content-divider"></div>
                <div class="container-fixed">
                    <div class="row quot-first-data">
                        <div class="col-xs-12 col-sm-12 no-padding">
                            <div class="col-sm-6 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Nombre comercial</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_commercial_name', $producto->prod_commercial_name, ['class' => 'form-control', 'id'=>'prod_commercial_name', 'required']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Vigencia (Desde)</div>
                                    <div class="quot-data-box-content">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                @php
                                                $date = \Carbon\Carbon::parse($producto->prod_valid_date_ini);
                                                $date->format('Y-m-d');
                                                @endphp
                                                {{ Form::date('prod_valid_date_start', $date, ['id'=>'quota_date_ini','class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Vigencia (Hasta)</div>
                                    <div class="quot-data-box-content">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                @php
                                                $date = \Carbon\Carbon::parse($producto->prod_valid_date_end);
                                                $date->format('Y-m-d');
                                                @endphp
                                                {{ Form::date('prod_valid_date_expire', $date, ['id'=>'product_date_end1','class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Unidades de presentación comercial</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_commercial_unit', $producto->prod_commercial_unit, ['class' => 'form-control', 'id'=>'prod_commercial_unit', 'required']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Linea</div>
                                    <div class="quot-data-box-content">
                                        {!! Form::select('id_prod_line',$product_lines, $producto->id_prod_line,['class'
                                        => 'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione',
                                        'required']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title"> Código SAP</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_sap_code', $sap, ['class' => 'form-control', 'id'=>'prod_sap_code']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Nombre Generico</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_generic_name', $producto->prod_generic_name, ['class' => 'form-control', 'id'=>'prod_generic_name', 'required'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Registro Invima</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_invima_reg', $producto->prod_invima_reg, ['class' => 'form-control', 'id'=>'prod_invima_reg','required'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Marca (Brand)</div>
                                    <div class="quot-data-box-content">
                                        {!! Form::select('id_brand',$brands, $producto->id_brand,['class' =>
                                        'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione',
                                        'required']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title"> Código CUM</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_cum', $producto->prod_cum, ['class' => 'form-control', 'id'=>'prod_cum', 'required'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Código IUM</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_cod_IUM', $producto->prod_cod_IUM,  ['class' => 'form-control', 'id'=>'prod_cod_IUM'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Código ATC</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_cod_ATC', $producto->prod_cod_ATC,  ['class' => 'form-control', 'id'=>'prod_cod_ATC'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title"> Código EAN (código de barras) </div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_cod_EAN', $producto->prod_cod_EAN,  ['class' => 'form-control', 'id'=>'prod_cod_EAN'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Concentracion del medicamento</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_concentration', $producto->prod_concentration,  ['class' => 'form-control', 'id'=>'prod_concentration'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="content-divider"></div>
                            <div class="col-sm-6 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Presentación - <em>Ingrese la descripción del
                                            producto.</em></div>
                                    <div class="quot-data-box-content">
                                        {{ Form::textarea('prod_package', $producto->prod_package, ['class' => 'form-control', 'id'=>'prod_package','rows'=>'6','required'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Unidades de presentación</div>
                                    <div class="quot-data-box-content">
                                        {{ Form::text('prod_package_unit', $producto->prod_package_unit, ['class' => 'form-control', 'id'=>'prod_package_unit', 'required'])}}
                                    </div>
                                </div>
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Unidad minima</div>
                                    <div class="quot-data-box-content">
                                        {!! Form::select('id_measure_unit',$measure_unit,
                                        $producto->id_measure_unit,['class'
                                        => 'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione',
                                        'required']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 no-padding">
                                <div class="quot-data-box-auto">
                                    <div class="quot-data-box-title">Normatividad precio del medicamento</div>
                                    <div class="quot-data-box-content">
                                        <label class="radiobtn">Regulado
                                            {{ Form::radio('is_prod_regulated',"REGULADO", $producto->is_prod_regulated == "REGULADO" ? true : false , ['class'=>'']) }}
                                            <span class="checkradio"></span>
                                        </label>
                                        <label class="radiobtn">Vigilancia
                                            {{ Form::radio('is_prod_regulated',"VIGILANCIA", $producto->is_prod_regulated == "VIGILANCIA" ? true : false , ['class'=>'']) }}
                                            <span class="checkradio"></span>
                                        </label>
                                        <label class="radiobtn">No aplica
                                            {{ Form::radio('is_prod_regulated',"NO APLICA", $producto->is_prod_regulated == "NO APLICA" ? true : false , ['class'=>'']) }}
                                            <span class="checkradio"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="quot-data-box-auto">
                                    <div class="quot-data-box-title">Dividr para ARP</div>
                                    <div class="quot-data-box-content">
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('arp_divide', 'true', $producto->arp_divide) !!}
                                                Dividir
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    @can('financiero')
                    <div class="row quot-first-data">
                        <table data-toggle="table" data-pagination="true" data-search="true"
                            class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Nombre de lista</th>
                                    <th data-sortable="true" class="text-center">Precio Vigente Comercial</th>
                                    <th data-sortable="true" class="text-center">Precio Vigente Institucional</th>
                                    <th data-sortable="true" class="text-center">Precio Máximo Regulado</th>
                                    <th data-sortable="true" class="text-center">Vigencia (Desde)</th>
                                    <th data-sortable="true" class="text-center">Vigencia (Hasta)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product_prices as $key => $price)
                                <tr id="ads" class="animated">
                                    <td>
                                        <a href="{{ route('prices.show',['price' => $price->list->id_pricelists ]) }}"><i
                                                class="fas fa-tags"></i> <strong>{{ $price->list->list_name }}</strong>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-dollar"></i>
                                        {{  number_format($price->v_commercial_price,0, ",", ".") }}
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-dollar"></i>
                                        {{ number_format($price->v_institutional_price,0, ",", ".") }}
                                    </td>
                                    <td class="text-center">
                                        @if ($price->prod_increment_max != "N/A")
                                        <i class="fa fa-dollar"></i>
                                        {{ number_format($price->prod_increment_max,0, ",", ".") }}
                                        @else
                                        <i class="fa fa-dollar"></i> {{ $price->prod_increment_max }}
                                        @endif

                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-calendar"></i>
                                        {{ date('Y-m-d', strtotime($price->prod_valid_date_ini)) }}
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-calendar"></i>
                                        {{ date('Y-m-d', strtotime($price->prod_valid_date_end)) }}
                                    </td>

                                </tr>
                                @endforeach
                                <!-- empty hidden one for jQuery -->
                                <tr id="ads" class="empty-row screen-reader-text animated">
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                            </div>
                                            {{ Form::text('v_commercial_price[]', null, ['class' => 'form-control', 'id'=>'v_commercial_price2']) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                            </div>
                                            {{ Form::text('v_institutional_price[]', null, ['class' => 'form-control', 'id'=>'v_institutional_price2']) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                            </div>
                                            {{ Form::text('prod_increment_max[]',null, ['class' => 'form-control', 'id'=>'prod_increment_max2']) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            {{ Form::date('prod_valid_date_ini[]', null, ['class' => 'form-control', 'id'=>'product_date_ini1']) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            {{ Form::date('prod_valid_date_end[]', null , ['class' => 'form-control', 'id'=>'product_date_end1']) }}
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-danger remove-row" type="button">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endcan
                    <div class="row quot-first-data renovacion">
                        <div class="col-12 col-sm-4 ">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Registro en trámite de renovación</div>
                                <div class="quot-data-box-content">
                                    <label class="checkbox-container">SI
                                        {{ Form::checkbox('renovacion',1, $producto->renovacion, false, ['class' => 'my-class']) }}
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Fecha máxima de extensión del sistema</div>
                                <div class="quot-data-box-content">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            @if (empty($producto->extension_time))
                                            {{ Form::date('extension_time', null , array('class' => 'form-control', 'id'=>'extension_time')) }}
                                            @else
                                            {{ Form::date('extension_time', date('Y-m-d', strtotime($producto->extension_time)) , array('class' => 'form-control', 'id'=>'extension_time')) }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-8 ">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Comentarios</div>
                                <div class="quot-data-box-content">
                                    <div class="input-group">
                                        {{ Form::textarea('comments', $producto->comments, ['class' => 'form-control', 'id'=>'comments'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="quot-data-box">
                                <div class="quot-number">
                                    <h3><i class="ion ion-compose"></i> Adjuntar documentos</h3>
                                </div>
                                <div class="col-xs-12 col-sm-12 no-padding">
                                    <div class="col-sm-12 no-padding">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Seleccione los documentos que desea
                                                adjuntar al producto</div>
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
                </div>
            </div>
            <div class="content-divider"></div>
            <div class="quot-send-btn">
                <input type="submit" value="Modificar" class="btn btn-novo-big">
            </div>
            {!! Form::close() !!}
    </section>
</div>
<!-- /.content -->
@endsection

@section('pagescript')
<Script>
    $( document ).ready(function() {
        $("#form_product_edit").keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        $('#prod_sap_code').tagsinput({
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
</Script>
<script>
    $("#file-1").fileinput({
        allowedFileExtensions: ['jpg', 'png', 'gif','doc','pdf','xls','docx','xlsx'],
        browseClass: "btn btn-primary btn-block",
        maxFileSize: 4000,
        maxFileCount: 10,
        showUpload: false,
        showRemove: false,
        showCaption: false,
        browseOnZoneClick: true,
        showBrowse: false,
        showDrag:true,
        uploadUrl: '#',
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });

    jQuery(document).ready(function($) {
        var rowCountIni = $('#repeatable-fieldset-one tr').length;
        if(rowCountIni > 2){
            $('#add-row').hide();
            startPicker();
            return false;
        }
    });

    jQuery(document).ready(function($) {

        var files = 0;

        $('#add-row').on('click', function() {

            var row = $('.empty-row.screen-reader-text').clone(true);
            row.removeClass('empty-row screen-reader-text');
            row.insertBefore('#repeatable-fieldset-one tbody>tr:last');

            startPicker();

            var rowCount = $('#repeatable-fieldset-one tr').length;
            if(rowCount >= 4){
                $('#add-row').hide();
                return false;
            }

            return false;
        });

        $('.remove-row').on('click', function() {
            $(this).parents('tr').remove();
            var rowCount = $('#repeatable-fieldset-one tr').length;
            console.log(rowCount);

            if(rowCount < 4){
                $('#add-row').show();
            }

            return false;
        });

    });


</script>
@endsection
