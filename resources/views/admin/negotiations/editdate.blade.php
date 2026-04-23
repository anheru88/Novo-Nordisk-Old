@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation"  id="app">
        <section class="content-header">
                <div class="bread-crumb" v-on:load="quotaEdit(3)">
                    <a href="#">Home</a> / Negociaciones / Negociación - {{ $negotiation->negotiation_consecutive }}

                </div>
                @if ($negotiation->is_authorized == 4  )
                    <div class="tools-header">
                    <div class="tools-menu-btn">
                        <div class="tools-menu-btn-icon"><i class="ion ion-printer"></i></div>
                        <div class="tools-menu-btn-text"><a href="{{ $negotiation->id_negotiation }}/pdf"> Imprimir</a></div>
                    </div>
                    @can('cotizaciones.edit')
                    <div class="tools-menu-btn pull-right lavared-bg white-text">
                        <div class="tools-menu-btn-icon"><i class="fas fa-trash-alt"></i></div>
                        <div class="tools-menu-btn-text" data-toggle="modal" data-target="#modal-anular"> Anular</div>
                    </div>
                    @endcan
                </div>
                @endif
        </section>
        {{-- @php
            dd($negotiation->id_negotiation);
        @endphp --}}
        {!! Form::model($negotiation, ['route' => ['negociaciones.updatedate', $negotiation->id_negotiation],
        'method'=>'POST', 'files' => 'false']) !!}
        <!-- Main content -->
        <section class="content">
            <div class="box box-info quot">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="quot-number">
                        <div class="col-xs-6">
                            <h1>Negociación - {{ $negotiation->negotiation_consecutive }}</h1>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Fecha de creación</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($negotiation->created_at )) }}</div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente desde</div>
                            <div class="quot-data-box-content">
                                {{ Form::date('negotiation_date_ini', \Carbon\Carbon::parse($negotiation->negotiation_date_ini), array('required','id'=>'negotiation_date_ini','class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente hasta</div>
                            <div class="quot-data-box-content">
                                {{ Form::date('negotiation_date_end', \Carbon\Carbon::parse($negotiation->negotiation_date_end), array('required','id'=>'negotiation_date_end','class' => 'form-control')) }}
                            </div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 col-sm-9 no-padding">
                                <div class="col-sm-6 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Cliente</div>
                                        <div class="quot-data-box-content">
                                            {{ $negotiation->cliente->client_name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Código de cliente</div>
                                        <div class="quot-data-box-content">{{ $negotiation->cliente->client_sap_code }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Ciudad</div>
                                        <div class="quot-data-box-content">{{ $location->loc_name }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Canal de venta</div>
                                        <div class="quot-data-box-content">
                                            {{ $negotiation->channel->channel_name}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Autorización</div>
                                        <div class="quot-data-box-content">
                                            {!! statusCot($negotiation->is_authorized, $negotiation->pre_aproved) !!}
                                        </div>

                                    </div>
                                </div>
                                @if ($autorizador != "")
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Autorizador</div>
                                        <div class="form-group">
                                            {{ $autorizador }}
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Condicion financiera</div>
                                        <div class="form-group">
                                            {{ getPaytermName($negotiation->cliente->id_payterm) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 no-padding">
                                <div class="quot-data-box level-gray-box">
                                    <div class="quot-auth-list">
                                        <div class="quot-auth-list-name">Nivel de autorización</div>
                                        <div class="quot-auth-list-number">
                                            @if ($negotiation->id_auth_level > 1)
                                                <strong>
                                                {{ $negotiation->id_auth_level }}
                                            </strong>
                                                @else
                                                    No requiere
                                                @endif
                                        </div>
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
<!-- /.content -->
@endsection

@section('pagescript')
<script src="{{ asset('js/negotiation_create.js') }}"></script>
<script src="{{ asset('js/generic.js') }}"></script>
@endsection