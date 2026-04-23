@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="product" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="/">Inicio</a> / <a href="{{ route('products.index') }}">Productos </a> / Crear producto
            </div>
            <h1>
                Crear nuevo producto
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
                @if(Session::has('success'))
                <div class="alert alert-info">
                    {{Session::get('success')}}
                </div>
                @endif
                <!-- /.box-header -->
                {!! Form::open(['action' => 'ProductsController@store', 'method' => 'POST', 'id' => 'form_product']) !!}
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="content-divider"></div>
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 col-sm-12 no-padding">
                                <div class="col-sm-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Nombre del producto</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-plus-square"></i>
                                                </div>
                                                {{ Form::text('prod_name', null, ['class' => 'form-control',
                                                'id'=>'client','required']) }}
                                            </div>
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
                                                    {{ Form::date('prod_valid_date_ini', null,
                                                    ['required','id'=>'quota_date_ini','class' => 'form-control']) }}
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
                                                    {{ Form::date('prod_valid_date_end', null,
                                                    ['required','id'=>'product_date_end1','class' => 'form-control']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 no-padding">
                                <div class="col-sm-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Nombre comercial</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_commercial_name', null, ['class' => 'form-control',
                                            'id'=>'client_quot','required']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title"> Código SAP</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_sap_code', null, ['required','class' => 'form-control',
                                            'id'=>'prod_sap_code', 'placeholder' => '']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Unidades de presentación comercial</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_commercial_unit', null, ['class' => 'form-control',
                                            'id'=>'prod_commercial_unit','required']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Linea</div>
                                        <div class="quot-data-box-content">
                                            {!! Form::select('id_prod_line',$product_lines, null,['class'
                                            => 'form-control focus filter-table-textarea', 'placeholder' =>
                                            'Seleccione',
                                            'required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Nombre Generico</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_generic_name', null, ['class' => 'form-control',
                                            'id'=>'client_sap_name'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Marca (Brand)</div>
                                        <div class="quot-data-box-content">
                                            {!! Form::select('id_brand',$brands, null,['class'
                                            => 'form-control focus filter-table-textarea', 'placeholder' =>
                                            'Seleccione',
                                            'required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Registro Invima</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_invima_reg', null, ['class' => 'form-control',
                                            'id'=>'client_sap_name'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title"> Código CUM</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_cum', null, ['class' => 'form-control',
                                            'id'=>'prod_cum'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Código IUM</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_cod_IUM', null, ['class' => 'form-control',
                                            'id'=>'prod_cod_IUM'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Código ATC</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_cod_ATC', null, ['class' => 'form-control',
                                            'id'=>'prod_cod_ATC'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title"> Código EAN (código de barras) </div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_cod_EAN', null, ['class' => 'form-control',
                                            'id'=>'prod_cod_EAN'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Concentracion del medicamento</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_concentration', null, ['class' => 'form-control',
                                            'id'=>'prod_concentration'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="content-divider"></div>
                                <div class="col-sm-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Presentación</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::textarea('prod_package', null, ['class' => 'form-control',
                                            'id'=>'client_sap_code','rows'=>'6','required'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Unidades de presentación</div>
                                        <div class="quot-data-box-content">
                                            {{ Form::text('prod_package_unit', null, ['class' => 'form-control',
                                            'id'=>'client_sap_code','required'])}}
                                        </div>
                                    </div>
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Unidad minima</div>
                                        <div class="quot-data-box-content">
                                            {!! Form::select('id_measure_unit',$measure_unit, null,['class'
                                            => 'form-control focus filter-table-textarea', 'placeholder' =>
                                            'Seleccione',
                                            'required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 no-padding">
                                    <div class="quot-data-box-auto">
                                        <div class="quot-data-box-title">Medicamento en control</div>
                                        <div class="quot-data-box-content">
                                            <label class="radiobtn">Regulado
                                                <input type="radio" value="REGULADO" name="is_prod_regulated">
                                                <span class="checkradio"></span>
                                            </label>
                                            <label class="radiobtn">Vigilancia
                                                <input type="radio" value="VIGILANCIA" name="is_prod_regulated">
                                                <span class="checkradio"></span>
                                            </label>
                                            <label class="radiobtn">No aplica
                                                <input type="radio" value="NO APLICA" name="is_prod_regulated">
                                                <span class="checkradio"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="quot-data-box-auto">
                                        <div class="quot-data-box-title">Dividr para ARP</div>
                                        <div class="quot-data-box-content">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="true" name="arp_divide">
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
                            <div class="col-xs-12 col-sm-12 no-padding">
                                <div class="col-12 col-sm-4 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Precio Vigente Comercial</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                {{ Form::text('v_commercial_price', null, array('required'), ['class' =>
                                                'form-control', 'id'=>'address'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Precio Vigente Institucional</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                {{ Form::text('v_institutional_price', null, array('required'), ['class'
                                                => 'form-control', 'id'=>'phone_number'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Tope máximo de incremento</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                {{ Form::text('prod_increment_max', null, ['class' => 'form-control',
                                                'id'=>'prod_increment_max','required'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>
                {{ Form::hidden('created_by', Auth::user()->id , array('required'), ['class' => 'form-control',
                'id'=>'phone_number'])}}

                <div class="content-divider"></div>
                <div class="quot-send-btn">
                    <input type="submit" value="Crear producto" class="btn btn-novo-big">
                </div>
                {!! Form::close() !!}
        </section>
    </div>
</div>
<!-- /.content -->
@endsection

@section('pagescript')
<Script>
    $( document ).ready(function() {
        $("#form_product").keypress(function(e) {
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
<script type="text/javascript">
    //$('input[name="prod_sap_code"]').amsifySuggestags();
</script>

@endsection
