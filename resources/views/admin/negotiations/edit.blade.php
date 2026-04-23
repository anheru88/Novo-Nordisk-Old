@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app" v-cloak>
        <section class="content-header">
            <div class="bread-crumb">
                <a href="#">Home</a> / Negociaciones / Negociación - {{ $negotiation->negotiation_consecutive }}
            </div>
        </section>
        <section class="content">
            <div class="box box-info quot">
                <div class="box-body">
                    <div class="quot-number">
                        <div class="col-xs-6">
                            <h1>Negociación - {{ $negotiation->negotiation_consecutive }}</h1>
                            {{ Form::hidden('client', $negotiation->id_client , array('id' => 'client')) }}
                            {{ Form::hidden('id_negotiation', $negotiation->id_negotiation , array('id' => 'id_negotiation')) }}
                            {{ Form::hidden('nego_date_ini', date('Y-m-d', strtotime($negotiation->negotiation_date_ini)), array('id' => 'nego_date_ini')) }}
                            {{ Form::hidden('nego_date_end', date('Y-m-d', strtotime($negotiation->negotiation_date_end)), array('id' => 'nego_date_end')) }}
                            {{ Form::hidden('authlevel', $negotiation->id_auth_level , array('id' => 'authlevel')) }}
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Fecha de creación</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($negotiation->created_at )) }}</div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente desde</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($negotiation->negotiation_date_ini))}}
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente hasta</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($negotiation->negotiation_date_end))}}
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
                                        <div class="quot-data-box-content">{{ $negotiation->cliente->client_sap_code }}
                                        </div>
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
                                            {{$negotiation->channel->channel_name}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Estado</div>
                                        <div class="quot-data-box-content">
                                            @if ($negotiation->status_id > 0)
                                            <div class="label" style="background-color:{{ $negotiation->status->status_color }}; font-size: 11px; border-radius: 3px;}">
                                                {{ $negotiation->status->status_name }}
                                            </div>
                                            @else
                                            {!! statusCot($quotation->is_authorized, $quotation->pre_aproved) !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Revisada por:</div>
                                        <div class="form-group">
                                            <div class="form-group">
                                                @foreach ($negotiation->approving as $approver)
                                                <div class="approver">
                                                    {{ $approver->approversUser->name }}
                                                    @if ($approver->answer != null)
                                                        <i class="fas fa-check"></i>
                                                    @endif
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 no-padding">
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
                                            <strong v-if="levelAuthQuota > 1">
                                                @{{ levelAuthQuota }}
                                            </strong>
                                            <strong v-else>
                                                No requiere
                                            </strong>
                                        </div>
                                        <input type="hidden" id="id_auth_level" name="id_auth_level" :value="levelAuthQuota">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row quot-add-product">
                        <div class="col-xs-12">
                            <div class="content-divider"></div>
                            <div class="quot-number">
                                <h3><i class="fa fa-barcode"></i> Comentarios</h3>
                            </div>
                        </div>
                        @if (sizeof($negotiation->usercomments) > 0)
                        <div class="col-md-12">
                            <ul class="timeline">
                                @foreach ($negotiation->usercomments as $comment)
                                <li>
                                    <i class="fa fa-user bg-aqua"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y / h:m:s',strtotime($comment->created_at)) }} <i class="fa fa-clock-o"></i>
                                        </span>
                                        <h3 class="timeline-header">{{ $comment->users->name }} - Estado: {{
                                            $comment->type_comment }} </h3>
                                        <div class="timeline-body">
                                            {{ $comment->text_comment }}
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 no-padding">
                                <div class="quot-data-box gray-box">
                                    <div class="quot-data-box-title">Documentos de soporte:</div>
                                    <div class="form-group">
                                        @foreach ($negotiation->documents as $doc)
                                        <div class="doc-quota">
                                            <a href="{{ asset($doc->file_folder.'/'.$doc->doc_name) }}" target="_blank"><i
                                                    class="fas fa-file-alt" ></i> {{ $doc->doc_name }}</a>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="content min-height-auto" v-if="client">
                        <div class="box box-info quot">
                            <div class="box-header with-border">
                                <h3><i class="fas fa-file-alt"></i> Detalles de la negociación</h3>
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
                                <div class="row quot-first-data">
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
                                    <div class="col-xs-12 col-sm-12 ">
                                        <div class="quot-number">
                                            <h3><i class="fa fa-barcode"></i> Agregar nuevos comentarios</h3>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Escriba los comentarios sobre la negociación</div>
                                            <div class="quot-data-box-content">
                                                <div class="input-group">
                                                    {{ Form::textarea('comment', null, ['class' => 'form-control comment-area', 'id'=>'comment'])}}
                                                    {!! Form::hidden('idNegotiation',  $negotiation->id_negotiation , ['id' => 'idNegotiation']) !!}
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
                                            <div class="quot-data-box-title">Seleccione los documentos que desea adjuntar a la cotización <br></div>
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
                <div class="quot-send-btn">
                </div>
        </section>
    </div>

</div>
<!-- /.content -->
@endsection

@section('pagescript')
<script src="{{ asset('js/negotiation_edit.js') }}"></script>
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
</script>
@endsection
