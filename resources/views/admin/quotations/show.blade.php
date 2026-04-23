@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="quotation">
        <section class="content-header">
            <div class="bread-crumb" v-on:load="quotaEdit(3)">
                <a href="#">Home</a> / Cotizaciones / Cotización - {{ $quotation->quota_consecutive }}

            </div>
            @if ($quotation->is_authorized == 6 || $quotation->status_id <= 6)
            <div class="tools-header">
                <div class="tools-menu-btn">
                    <div class="tools-menu-btn-icon"><i class="ion ion-printer"></i></div>
                    <div class="tools-menu-btn-text"><a href="{{ $quotation->id_quotation }}/pdf"> Imprimir</a></div>
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
        <section class="content">
            <div class="box box-info quot">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="quot-number">
                        <div class="col-xs-6">
                            <h1>Cotización - {{ $quotation->quota_consecutive }}</h1>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Fecha de creación</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($quotation->created_at )) }}</div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente desde</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($quotation->quota_date_ini))}}</div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente hasta</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($quotation->quota_date_end))}}</div>
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
                                            {{ $quotation->cliente->client_name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Código de cliente</div>
                                        <div class="quot-data-box-content">{{ $quotation->cliente->client_sap_code }}
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
                                            {{ $quotation->channel->channel_name}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Autorización</div>
                                        <div class="quot-data-box-content">
                                            @if ($quotation->status_id > 0)
                                            <div class="label"
                                                style="background-color:{{ $quotation->status->status_color }}; font-size: 11px; border-radius: 3px;}">
                                                {{ $quotation->status->status_name }}
                                            </div>
                                            @else
                                            {!! statusCot($quotation->is_authorized, $quotation->pre_aproved) !!}
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
                                            {{ getPaytermName($quotation->cliente->id_payterm) }}
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
                                        <div class="quot-auth-list-number">
                                            @if ($quotation->id_auth_level > 1)
                                            <strong>
                                                {{ $quotation->id_auth_level }}
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
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 no-padding">
                                <div class="quot-data-box gray-box">
                                    <div class="quot-data-box-title">Documentos de soporte:</div>
                                    <div class="form-group">
                                        @foreach ($documentos as $doc)
                                        <div class="doc-quota">
                                            <a href="{{ asset($doc->file_folder.'/'.$doc->doc_name) }}"><i
                                                    class="fas fa-file-alt"></i> {{ $doc->doc_name }}</a>
                                        </div>
                                        @endforeach
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
                                    <h3><i class="fa fa-barcode"></i> Productos cotizados</h3>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table id="quotation_show" data-toggle="table" data-pagination="true" data-search="true"
                                    class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">Producto</th>
                                            <th data-sortable="true" class="text-center">Cantidad</th>
                                            <th data-sortable="true" class="text-center">PRECIO UNIDAD MÍNIMA</th>
                                            <th data-sortable="true" class="text-center">PRECIO PRESENTACIÓN COMERCIAL
                                            </th>
                                            <th data-sortable="true" class="text-center">PRECIO TOTAL COTIZADO</th>
                                            <th data-sortable="true" class="text-center">AJUSTES AL PRECIO</th>
                                            <th data-sortable="true" class="text-center">CONDICIONES FINANCIERAS</th>
                                            <th data-sortable="true"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($productos->count())
                                        @foreach($productos as $producto)
                                        <tr @if ($producto->is_valid == 0 || $producto->is_valid == 8) class="bg-red" @endif>
                                            <td>
                                                {{ $producto->product->prod_name }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format( $producto->quantity,0, ",", ".") }}
                                            </td>
                                            <td class="text-center">
                                                ${{ number_format( $producto->price_uminima,1, ",", ".") }}</td>
                                            <td class="text-center">
                                                ${{ number_format( $producto->totalValue,0, ",", ".") }}</td>
                                            <td class="text-center">
                                                ${{ number_format( $producto->totalValue,0, ",", ".") }}
                                            </td>
                                            <td class="text-center">{{ $producto->pay_discount }}%</td>
                                            <td class="text-center">
                                                {{ $producto->payterm->payterm_name }}
                                            </td>
                                            <td>
                                                @if ($producto->is_valid == 0 || $producto->is_valid == 8)
                                                <div class="badge bg-red">
                                                    <i class="fas fa-ban"></i> Sin vigencia
                                                </div>
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
                    <!-- /.datos de clientet -->
                    @if (sizeof($quotation->usercomments) > 0)
                    <div class="container-fixed">
                        <section>
                            <!-- row -->
                            <div class="row quot-add-product">
                                <div class="col-xs-12">
                                    <div class="quot-number">
                                        <h3><i class="fa fa-barcode"></i> Comentarios</h3>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <ul class="timeline">
                                        @foreach ($quotation->usercomments as $comment)
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
                            </div>
                        </section>
                    </div>
                    @endif
                </div>
                <div class="quot-send-btn">
                </div>
        </section>

        <!-- /.modal -->

        <div class="modal fade" id="modal-anular">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route' => ['cotizaciones.anular',$quotation->id_quotation], 'method' => 'PUT',
                    'files'
                    =>
                    'false']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h2 class="modal-title">¿Realmente desea anular la Cotizacion
                            #{{ $quotation->quota_consecutive }}?</h2>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 no-padding">
                            <div class="quot-data-box">
                                <label>Ingrese los comentarios de por qué anula la cotización*</label>
                                <div class="form-group">
                                    {!! Form::textarea('comments', null, ['class' => 'form-control',
                                    'id'=>'comments','required']) !!}
                                    {!! Form::hidden('id_quotation', $quotation->id_quotation) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> CANCELAR </a>
                        {{ Form::button('<i class="fas fa-check"></i> Anular ', ['type' => 'submit', 'class' => 'btn
                        btn-sm btn-success'] ) }}
                    </div>

                    {!! Form::close() !!}
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
@endsection
