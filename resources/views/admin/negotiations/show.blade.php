@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="quotation">
        <section class="content-header">
            <div class="bread-crumb" v-on:load="quotaEdit(3)">
                <a href="#">Home</a> / Negociaciones / Negociación - {{ $negotiation->negotiation_consecutive }}
            </div>
            <div class="tools-header">
                @if ($negotiation->status_id == 6 )
                <div class="tools-menu-btn">
                    <div class="tools-menu-btn-icon"><i class="ion ion-printer"></i></div>
                    <div class="tools-menu-btn-text"><a href="{{ $negotiation->id_negotiation }}/pdf"> Imprimir</a>
                    </div>
                </div>
                @endif
                @if ($negotiation->status_id < 7 )
                @can('negociaciones.edit')
                <div class="tools-menu-btn pull-right lavared-bg white-text" data-toggle="modal" data-target="#modal-anular">
                    <div class="tools-menu-btn-icon"><i class="fas fa-trash-alt"></i></div>
                    <div class="tools-menu-btn-text" > Anular</div>
                </div>
                @endcan
                @endif
            </div>
        </section>
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
                                            {{ $negotiation->channel->channel_name}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
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
                                        <input type="hidden" id="id_auth_level" name="id_auth_level"
                                            :value="levelAuthQuota">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <!-- /.datos de clientet -->
                    <div class="container-fixed">
                        <div class="row quot-add-product">
                            <div class="col-xs-12">
                                <div class="quot-number">
                                    <h3><i class="fa fa-barcode"></i> Productos negociados</h3>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table id="negotiations" data-toggle="table" data-pagination="true" data-search="true"
                                    class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">PRODUCTO</th>
                                            <th data-sortable="true" class="text-center">NEGOCIACIÓN #</th>
                                            <th data-sortable="true" class="text-center">DESCUENTO</th>
                                            <th data-sortable="true" class="text-center">DESCUENTO ACUMULADO</th>
                                            <th data-sortable="true" class="text-center">CONCEPTO</th>
                                            <th data-sortable="true" class="text-center">ACLARACIÓN</th>
                                            <th data-sortable="true" class="text-center">SUJETO A VOLUMEN</th>
                                            <th data-sortable="true" class="text-center">OBSERVACIONES</th>
                                            <th data-sortable="true" class="text-center">VISIBLE</th>
                                            <th data-sortable="true" class="text-center">ADVERTENCIAS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($negotiation->negodetails->count())
                                        @foreach($negodetails as $producto)
                                        <tr @if ($producto->is_valid == 0 || $producto->is_valid >= 7) class="bg-red" @endif>
                                            <td>
                                                {{ $producto->product->prod_name }}
                                            </td>
                                            <td class="text-center"><a
                                                    href="{{ route('cotizaciones.show',['cotizacione' => $producto->id_quotation ]) }}">{{
                                                    $producto->quotation->quota_consecutive}}</a>
                                            </td>
                                            <td class="text-center">{{ $producto->discount }}%</td>
                                            <td class="text-center">{{ $producto->discount_acum }}%</td>
                                            @if ($producto->id_concept > 0 && isset($producto->concept->name_concept))
                                            <td class="text-center uppercase">{{ $producto->concept->name_concept }}
                                            </td>
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
                                            <td class="text-center">
                                                @if ($producto->warning >= 1)
                                                    <div class="right-warning">
                                                        @foreach ($producto->errors as $error)
                                                        <div class="warning-show"><i class="fas fa-exclamation-triangle"></i></div>
                                                        <div class="right-description">{{ $error->negotiation_error }}</div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    N/A
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
                    <!-- /.Lista de productos -->
                    <div class="content-divider"></div>
                    <div class="container-fixed">
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
                                                <span class="time"><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y / h:m:s',strtotime($comment->created_at)) }} <i class="fa fa-clock-o"></i> </span>
                                                <h3 class="timeline-header">{{ $comment->users->name }} - Estado:  {{ $comment->type_comment }} </h3>
                                                <div class="timeline-body">
                                                    {{ $comment->text_comment  }}
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </section>
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
                </div>
        </section>
    </div>
</div>
@include('admin.negotiations.modals.cancelnegotiation')
<!-- /.content -->
@endsection
@section('pagescript')
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
@endsection
