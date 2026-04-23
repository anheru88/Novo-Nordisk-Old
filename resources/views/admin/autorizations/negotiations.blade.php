@extends('admin.layout')
@section('content')
<div class="content-wrapper" id="app">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="quotation">
        <section class="content-header">
            <div class="bread-crumb" v-on:load="quotaEdit(3)">
                <a href="#">Home</a> / Autorizaciones / Negociación - {{ $negotiation->negotiation_consecutive }}
            </div>
            @if ($negotiation->is_authorized == 4 )
            <div class="tools-header">
                <div class="tools-menu-btn">
                    <div class="tools-menu-btn-icon"><i class="ion ion-printer"></i></div>
                    <div class="tools-menu-btn-text"><a href="{{ $negotiation->id_negotiation }}/pdf"> Imprimir</a>
                    </div>
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
        <!-- Main content -->
        {!! Form::open(['route' => ['autorizaciones.negociacion',$negotiation], 'method' => 'PUT', 'files' =>
        'true', 'v-on:submit' => 'authNego', 'ref'=>'authNego']) !!}
        <section class="content m-0">
            <div class="box box-info quot no-padding m-0">
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
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($negotiation->negotiation_date_ini))}}</div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente hasta</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($negotiation->negotiation_date_end))}}</div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 col-sm-9 no-padding">
                                <div class="row">
                                    <div class="col-sm-6 ">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Cliente</div>
                                            <div class="quot-data-box-content">
                                                {{ $negotiation->cliente->client_name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 no-padding">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Código de cliente</div>
                                            <div class="quot-data-box-content">
                                                {{ $negotiation->cliente->client_sap_code }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 no-padding">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Ciudad</div>
                                            <div class="quot-data-box-content">{{ $negotiation->city->loc_name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 no-padding">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Autorización</div>
                                            <div class="quot-data-box-content">
                                                @if ($negotiation->status_id > 0)
                                                <div class="label" style="background-color:{{ $negotiation->status->status_color }}; font-size: 11px; border-radius: 3px;}">
                                                    {{ $negotiation->status->status_name }}
                                                </div>
                                                @else
                                                {!! statusCot($negotiation->is_authorized, $negotiation->pre_aproved) !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2 ">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Canal de venta</div>
                                            <div class="quot-data-box-content">
                                                {{ $negotiation->channel->channel_name}}
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
                                    <div class="col-sm-4 no-padding">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Autorizadores</div>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    @foreach ($approvers as $approver)
                                                    <div class="approver">
                                                        {{ $approver->approversUser->name }}
                                                        @if ($approver->answer != null)
                                                            <i class="fas fa-check"></i>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                    <input type="hidden" name="respuesta" :value="approved">
                                                </div>
                                            </div>
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
                                    </div>
                                </div>
                            </div>
                            @if ($negotiation->comments != "")
                            <div class="container-fixed">
                                <div class="row quot-first-data">
                                    <div class="col-xs-12 no-padding">
                                        <div class="quot-data-box gray-box">
                                            <div class="quot-data-box-title">Comentarios del CAM
                                                ({{ $negotiation->creator->name }}):</div>
                                            <div class="form-group">{{ $negotiation->comments }}</div>
                                            @endif
                                            @if ($negotiation->comments_pre != "")
                                            <div class="quot-data-box-title">Comentarios del preautorizador:</div>
                                            <div class="form-group">{{ $negotiation->comments_pre }}</div>
                                            @endif
                                            @if ($negotiation->comments_auth != "")
                                            <div class="quot-data-box-title">Comentarios del autorizador:</div>
                                            <div class="form-group">{{ $negotiation->comments_auth }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
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
                            <div class="content-divider"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="box box-info quot">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fas fa-file-alt"></i> Productos negociados</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- /.datos de clientet -->
                    <div class="container-fixed">
                        <div class="row quot-add-product">
                            <div class="col-xs-12">
                                <table id="negotiations" data-toggle="table" data-pagination="true" data-search="true" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">PRODUCTO</th>
                                            <th data-sortable="true" class="text-center">Cotización #</th>
                                            <th data-sortable="true" class="text-center">DESCUENTO</th>
                                            <th data-sortable="true" class="text-center">DESCUENTO ACUMULADO</th>
                                            <th data-sortable="true" class="text-center">CONCEPTO</th>
                                            <th data-sortable="true" class="text-center">ACLARACIÓN</th>
                                            <th data-sortable="true" class="text-center">SUJETO A VOLUMEN</th>
                                            <th data-sortable="true" class="text-center">OBSERVACIONES</th>
                                            <th data-sortable="true" class="text-center">VISIBLE</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($negotiation->negodetails->count())
                                        @foreach($negodetails as $producto)
                                        <tr @if ($producto->is_valid == 0) class="bg-red" @endif>
                                            <td>
                                                {{ $producto->product->prod_name }}
                                            </td>
                                            <td class="text-center"><a
                                                    href="{{ route('cotizaciones.show',['cotizacione' => $producto->id_quotation ]) }}">{{ $producto->quotation->quota_consecutive}}</a>
                                            </td>
                                            <td class="text-center">{{ $producto->discount }}%</td>
                                            <td class="text-center">{{ $producto->discount_acum }}%</td>
                                            @if ($producto->id_concept > 0)
                                            <td class="text-center uppercase">{{ $producto->concept->name_concept }}</td>
                                            @else
                                            <td class="text-center uppercase">Escala</td>
                                            @endif
                                            @if ($producto->aclaracion != "")
                                            <td class="text-center">{{ $producto->aclaracion }}</td>
                                            @else
                                            <td class="text-center">N/A</td>
                                            @endif
                                            <td class="text-center upperc">{{ $producto->suj_volumen }}</td>
                                            <td class="text-center">{{ $producto->observations}}</td>
                                            <td class="text-center">{{ $producto->visible}} </td>
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
                    <div class="content-divider"></div>
                    <!-- /.datos de clientet -->
                    <section>
                        <!-- row -->
                        <div class="row quot-add-product">
                            <div class="col-xs-12">
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
                                            <span class="time"><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y /
                                                h:m:s',strtotime($comment->created_at)) }} <i
                                                    class="fa fa-clock-o"></i> </span>
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
                            <div class="col-xs-12 col-sm-12">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Agregar nuevos comentarios</div>
                                    <div class="quot-data-box-content">
                                        <div class="input-group">
                                            {{ Form::textarea('comment', null, ['class' => 'form-control', 'id'=>'comment', 'rows'=> 5])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="content-divider"></div>
                        <section>
                            <div class="row quot-add-product">
                                <div class="col-xs-12">
                                    <div class="quot-number">
                                        <h3><i class="fa fa-barcode"></i> Documentos</h3>
                                    </div>
                                </div>
                                <div class="col-sm-12 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Seleccione los documentos que desea adjuntar a
                                            la aprobación</div>
                                        <div class="quot-data-box-content">
                                            <div class="file-loading">
                                                {!! Form::file ('docs[]', ['class'=>'file', 'type'=>'file',
                                                'id'=>'file-1' ,'multiple']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <!-- /.Lista de productos -->
                    <div class="quot-send-btn-accept">
                        {{ $answer }}
                        <div class="tools-btns-gen">
                            {{ Form::button('<i class="fas fa-paper-plane"></i> Aprobar', ['type' => 'submit', 'class'=>
                            'btn tools-menu-btn btn-info buttons-accept pull-right white-text', 'v-on:click' => 'approved ='.$answer
                            ]) }}
                        </div>
                    </div>
                    <div class="quot-send-btn-reject">
                        <div class="tools-btns-gen">
                            {{ Form::button('<i class="fas fa-times"></i> Rechazar', ['type' => 'submit', 'class'=> 'btn
                            tools-menu-btn buttons-accept pull-right lavared-bg white-text', 'v-on:click' => 'approved = 7'
                            ]) }}
                        </div>
                    </div>
                    <div class="quot-send-btn-return">
                        <div class="tools-btns-gen">
                            {{ Form::button('<i class="fas fa-reply"></i> Solicitar correcciones', ['type' => 'submit', 'class'=> 'btn
                            tools-menu-btn buttons-accept pull-right lavared-bg white-text', 'v-on:click' => 'approved = 2'
                            ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {!! Form::close() !!}
    </div>
</div>
<!-- /.content -->
@endsection

@section('pagescript')
<script src="{{ asset('js/utilities.js') }}"></script>
<script>
    $("#file-1").fileinput({
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
