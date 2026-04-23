@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="quotation">
        <section class="content-header">
            <div class="bread-crumb" v-on:load="quotaEdit(3)">
                <a href="#">Inicio</a> / Cotizaciones / Editar cotización - {{ $quotation->quota_consecutive }}
            </div>
        </section>
        {!! Form::model($quotation, ['route' => ['cotizaciones.updatedate', $quotation->id_quotation],
        'method'=>'POST', 'files' => 'false']) !!}
        <input name="id_quotation" type="hidden" id="quotaID" value="{{ $quotation->id_quotation }}">
        <input type="hidden" id="level" name="id_auth_level" value="{{ $quotation->id_auth_level }}">
        <section class="content">
            <div class="box box-info quot">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="quot-number">
                        <div class="col-xs-12 col-sm-4 col-md-6">
                            <h1>Cotización - {{ $quotation->quota_consecutive }}</h1> (Modo Edición) <i
                                class="fa fa-edit"></i>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-2">
                            <div class="quot-data-box-title">Fecha de creación</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($quotation->created_at )) }}</div>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-2">
                            <div class="quot-data-box-title">Vigente desde</div>
                            <div class="quot-data-box-content">
                                {{ Form::date('quota_date_ini', \Carbon\Carbon::parse($quotation->quota_date_ini), array('required','id'=>'quota_date_ini','class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2">
                            <div class="quot-data-box-title">Vigente hasta</div>
                            <div class="quot-data-box-content">
                                {{ Form::date('quota_date_end', \Carbon\Carbon::parse($quotation->quota_date_end), array('required','id'=>'quota_date_end','class' => 'form-control')) }}
                            </div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 col-sm-9 no-padding">
                                <div class="col-xs-12 col-sm-12 col-md-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Cliente</div>
                                        <div class="quot-data-box-content">
                                            {{ $quotation->cliente->client_name }}
                                            <input type="hidden" id="idClient" name="id_client"
                                                value="{{ $quotation->cliente->id_client}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Código SAP de cliente</div>
                                        <div class="quot-data-box-content">{{ $quotation->cliente->client_sap_code }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Ciudad</div>
                                        <div class="quot-data-box-content">{{ $location->loc_name }}</div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3  no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Canal de venta</div>
                                        <div class="quot-data-box-content">
                                            {{ $quotation->channel->channel_name}}
                                            <input type="hidden" id="id_client_channel" value="{{ $quotation->channel->channel_name}}">
                                            <input type="hidden" id="id_client_channel_id" value="{{ $quotation->id_channel }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Autorización</div>
                                        <div class="quot-data-box-content">
                                                {!! statusCot($quotation->is_authorized, $quotation->pre_aproved) !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 no-padding">
                                <div class="quot-data-box gray-box">
                                    <div class="quot-data-box-title">Valor de la cotización</div>
                                    <div class="quot-data-box-precio">
                                        ${{ number_format( $quotation->quota_value,0, ",", ".") }}
                                    </div>
                                </div>
                                <div class="quot-data-box level-gray-box">
                                    <div class="quot-auth-list">
                                        <div class="quot-auth-list-name">Nivel de autorización</div>
                                        <div class="quot-auth-list-number"><strong>{{ $quotation->id_auth_level }}</strong></div>
                                        <input type="hidden" id="id_auth_level" name="id_auth_level" :value="levelAuthQuota">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-divider"></div>
                <div class="quot-send-btn">
                    {{ Form::button('Modificar', ['type' => 'submit', 'class' => 'btn btn-novo-big'] ) }}
                </div>
        </section>
        {!! Form::close() !!}
    </div>
</div>
<!-- /.modal -->
<!-- /.content -->
@endsection
@section('pagescript')
<script src="{{ asset('js/quotation_create.js') }}"></script>
@endsection
