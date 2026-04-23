@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app" v-cloak>
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / <a
                    href="{{  route('cotizaciones.index') }}">Negociaciones</a> / Crear negociación
            </div>
            <h1>
                Crear negociación
            </h1>
        </section>
        <!-- Main content -->
        <section class="content min-height-auto">
            <div class="box box-info quot">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row quot-first-data">
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label>Seleccione un cliente de la lista</label>
                                {!! Form::select('id_client',$clientes, null,['class'=> ' clientes-select form-control
                                focus filter-table-textarea', 'placeholder' => 'Seleccione', 'required', 'v-model' => 'client', 'id' => 'client']) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="date-col-1">
                                <div class="form-group">
                                    <label>Vigente desde</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        {{ Form::date('negotiation_date_ini', null,
                                        array('required','id'=>'quota_date_ini','class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="date-col-2">
                                <div class="form-group">
                                    <label></label>
                                    <div class="input-group">
                                        <label class="checkbox-container">Hoy
                                            <input type="checkbox" value="" id="checkday" @change="setDate()">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3 no-padding">
                            <div class="date-col-3">
                                <div class="form-group">
                                    <label>Vigente hasta</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        {{ Form::date('negotiation_date_end', null, array('required', 'class' =>
                                        'form-control', 'id'=>'quota_date_end')) }}
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="date-col-4">
                                <div class="form-group">
                                    <label>x Días</label>
                                    <div class="input-group">
                                        {{ Form::number('days', null, ['class' => 'form-control', 'id'=>'days',
                                        'v-on:change' => 'setDays', 'data-placement' => 'bottom',
                                        'title' => 'Ingrese el número de días calendario de la vigencia']) }}
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-2 no-padding">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="input-group">
                                    <a class="btn btn-bluegen btn-sm" v-on:click="getProductsClientQuota">CARGAR CLIENTE</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="content min-height-auto" v-if="client">
            <div class="box box-info quot">
                <div class="box-header with-border">
                    <h3><i class="fas fa-file-alt"></i> Detalles de la nueva negociación</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fas fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="spinner" v-if="loading">
                    <div class="orbit-spinner">
                        <div class="orbit"></div>
                        <div class="orbit"></div>
                        <div class="orbit"></div>
                    </div>
                </div>
                <div class="box-body" v-else>
                    <div class="row quot-first-data" v-if="showProducts">
                        <div class="col-xs-12">
                            <div class="col-xs-12 col-sm-9 no-padding">
                                <div class="col-sm-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Cliente</div>
                                        <div class="quot-data-box-content">
                                            @{{ client_name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Código de cliente</div>
                                        <div class="quot-data-box-content">@{{ client_code }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Ciudad</div>
                                        <div class="quot-data-box-content">@{{ client_city }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Canal de venta</div>
                                        <div class="quot-data-box-content">@{{ client_channel }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Condicion financiera</div>
                                        <div class="form-group">
                                            @{{ client_payterm }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Tipo de cliente</div>
                                        <div class="form-group">
                                            @{{ client_type }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 no-padding">
                                <div class="quot-data-box level-gray-box">
                                    <div class="quot-auth-list">
                                        <div class="quot-auth-list-name">Nivel de autorización</div>
                                        <div class="quot-auth-list-number">
                                            @{{ levelAuthQuota }}
                                        </div>
                                        <input type="hidden" id="id_auth_level" name="id_auth_level"
                                            :value="levelAuthQuota">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-5 no-padding">
                                <div class="alertdate" v-if="showWarning">
                                    <i class="fas fa-exclamation-circle"></i> <strong> Alerta:</strong> La negociación  excede los 365 dias.
                                </div>
                            </div>
                        </div>
                        <div class="content-divider"></div>
                        @include('admin.negotiations.partials.quotation_products')
                        <!-- /nego-col -->
                        @include('admin.negotiations.partials.negotiation_products')
                        <div class="col-xs-12 no-padding">
                            <div class="container-fixed left-products" v-if="showProducts">
                                <div class="row quot-add-product">
                                    <div class="col-xs-12">
                                        @include('admin.negotiations.partials.add_discount')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="content min-height-auto">
            <div class="box box-info quot">
                <div class="box-body">
                    <div class="row quot-first-data">
                        {!! Form::open(['action' => 'NegotiationsController@storeNegotiationFiles','method'=> 'POST', 'files' => 'true', 'id' => 'sendFiles']) !!}
                        <div class="content-divider"></div>
                        <div class="col-xs-12 col-sm-12 ">
                            <div class="quot-number">
                                <h3><i class="fa fa-barcode"></i> Comentarios</h3>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Escriba los comentarios sobre la negociación</div>
                                <div class="quot-data-box-content">
                                    <div class="input-group">
                                        {{ Form::textarea('comment', null, ['class' => 'form-control comment-area', 'id'=>'comment'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content-divider"></div>
                        <div class="col-xs-12 col-sm-12 ">
                            <div class="quot-number">
                                <h3><i class="fas fa-barcode"></i> Documentos adjuntos</h3></span>
                            </div>
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Seleccione los documentos que desea adjuntar a la
                                    cotización <br></div>
                                    {!! Form::hidden('idNegotiation', null, ['id' => 'idNegotiation']) !!}
                                <div class="quot-data-box-content">
                                    <div class="file-loading">
                                        {!! Form::file ('docs[]', ['class'=>'file', 'type'=>'file', 'id'=>'files','multiple']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="quot-send-btn" v-if="showProducts">
                            {{ Form::button('Guardar negociación', ['type' => 'submit', 'class' => 'btn btn-novo-big',  'v-on:click'=> 'sendNegotiation'] ) }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


@endsection

@section('pagescript')

<script src="{{ asset('js/negotiation_create.js') }}"></script>
<script>
    $(document).ready(function () {
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });

    $("#files").fileinput({
        allowedFileExtensions: ['jpg', 'png', 'gif','doc','pdf','xls','docx','xlsx','xls'],
        browseClass: "btn btn-primary btn-block",
        maxFileSize: 4000,
        maxFileCount: 5,
        showUpload: false,
        showRemove: false,
        showCaption: false,
        browseOnZoneClick: true,
        showBrowse: false,
        showDrag:true,
        //allowedFileTypes: ['image', 'video', 'flash'],

    });

    function toggle(source) {
        console.log(source);
        checkboxes = document.getElementsByName('products');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>
@endsection
