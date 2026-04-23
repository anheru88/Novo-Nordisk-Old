@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation">
        <section class="content-header">
            <div class="bread-crumb" v-on:load="quotaEdit(3)">
                <a href="#">Home</a> / Cotizaciones / Cotización- {{ $quotation->quota_consecutive }}
            </div>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-info quot">
                <!-- /.box-header -->
                <div class="box-body">
                    {!! Form::open(['route' => ['autorizaciones.pre',$quotation], 'method' => 'PUT', 'files' => 'false']) !!}
                    {{ csrf_field() }}
                    <div class="quot-number">
                        <div class="col-xs-6"><h1>Cotización - {{ $quotation->quota_consecutive }}</h1></div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Fecha de creación</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>{{ date('Y-m-d', strtotime($quotation->created_at )) }}</div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente desde</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>{{ date('Y-m-d', strtotime($quotation->quota_date_ini))}}</div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente hasta</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>{{ date('Y-m-d', strtotime($quotation->quota_date_end))}}</div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 col-sm-9 no-padding">
                                <div class="col-sm-5 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Cliente</div>
                                        <div class="quot-data-box-content">{{ $quotation->cliente->client_name }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Código de cliente</div>
                                        <div class="quot-data-box-content">{{ $quotation->cliente->client_sap_code }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Ciudad</div>
                                        <div class="quot-data-box-content">{{ $location->loc_name }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Canal de venta</div>
                                        <div class="quot-data-box-content">{{ $quotation->channel->channel_name}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-5 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Autorizador</div>
                                        <div class="form-group">
                                            <div class="form-group">
                                                {!! Form::select('id_auhorizer',$autorizadores, null,['class'=> 'productos form-control
                                                focus filter-table-textarea', 'placeholder' => 'Seleccione','id' => 'autorizadores','required']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Autorización</div>
                                        <div class="quot-data-box-content">{!! statusCot($quotation->is_authorized, $quotation->pre_aproved) !!}</div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-3 no-padding">
                                <div class="quot-data-box gray-box">
                                    <div class="quot-data-box-title">Valor de la cotización</div>
                                    <div class="quot-data-box-precio">${{ number_format( $quotation->quota_value,0, ",", ".") }}</div>
                                </div>
                                <div class="quot-data-box level-gray-box">
                                    <div class="quot-auth-list">
                                        <div class="quot-auth-list-name">Nivel de autorización</div>
                                        <div class="quot-auth-list-number"><strong>{!! $quotation->id_auth_level !!}</strong></div>
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
                                <table data-toggle="table" data-pagination="true" data-search="true" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">Producto</th>
                                            <th data-sortable="true" class="text-center">Cantidad</th>
                                            <th data-sortable="true" class="text-center">PRECIO UNIDAD MÍNIMA</th>
                                            <th data-sortable="true" class="text-center">PRECIO PRESENTACIÓN COMERCIAL</th>
                                            <th data-sortable="true" class="text-center">PRECIO TOTAL COTIZADO</th>
                                            <th data-sortable="true" class="text-center">AJUSTES AL PRECIO</th>
                                            <th data-sortable="true" class="text-center">CONDICIONES FINANCIERAS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($productos->count())
                                            @foreach($productos as $producto)
                                                <tr>
                                                    <td><a href="{{ route('products.show',['product' => $producto->id_quota_det]) }}">{{ $producto->prod_name }}</a></td>
                                                    <td class="text-center">{{ number_format( $producto->quantity,0, ",", ".") }}</td>
                                                    <td class="text-center">${{ number_format( $producto->v_commercial_price,0, ",", ".") }}</td>
                                                    <td class="text-center">${{ number_format( $producto->v_commercial_price,0, ",", ".") }}</td>
                                                    <td class="text-center">${{ number_format( $producto->totalValue,0, ",", ".") }}</td>
                                                    <td class="text-center">{{ $producto->pay_discount }}%</td>
                                                    <td class="text-center">{{ $producto->payterm_name }}</td>
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
                    <div class="container-fixed">
                        <div class="row quot-add-product">
                            <div class="col-xs-12">
                                <div class="quot-number">
                                    <h3><i class="fa fa-barcode"></i> Comentarios</h3>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Escriba los comentarios sobre la cotización</div>
                                    <div class="quot-data-box-content">
                                        <div class="input-group">
                                            {{ Form::textarea('comment', null, ['class' => 'form-control', 'id'=>'comment'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">&nbsp;</div>
                            <div class="col-xs-12"></div>
                            <div class="col-xs-12"></div>
                            <div class="col-xs-12 col-sm-12 ">
                                <div class="col-sm-12 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Seleccione los documentos que desea adjuntar a la preaprobación</div>
                                        <div class="quot-data-box-content">
                                            <div class="file-loading">
                                                {!! Form::file ('docs[]', ['class'=>'file', 'type'=>'file', 'id'=>'file-1' ,'multiple']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.Lista de productos -->
                    @can('preautorizacion')
                    <div class="quot-send-btn-accept">
                        <div class="tools-btns-gen">
                            <input type="hidden" name="respuesta" value="1">
                            <input type="hidden" name="prerespuesta" value="1">
                            {{ Form::button('<i class="fas fa-paper-plane"></i> Enviar a autorizador', ['type' => 'submit', 'class'=> 'btn  tools-menu-btn buttons-accept pull-right  white-text' ]) }}
                        </div>
                    </div>
                    @endcan
                    {!! Form::close() !!}
                    @can('preautorizacion')
                    <div class="quot-send-btn-reject">
                        <div class="tools-btns-gen">
                            {!! Form::open(['route' => ['autorizaciones.responder',$quotation], 'method' => 'PUT', 'files' => 'false']) !!}
                            {{ csrf_field() }}
                            <input type="hidden" name="respuesta" value="5">
                            <input type="hidden" name="prerespuesta" value="1">
                            {{ Form::button('<i class="fas fa-times"></i> Rechazar', ['type' => 'submit', 'class'=> 'btn  tools-menu-btn buttons-accept pull-right lavared-bg white-text' ]) }}
                            {!! Form::close() !!}
                        </div>
                    </div>
                    @endcan
                        @can('autorizaciones.show')
                        <div class="quot-send-btn">
                            <div class="tools-btns-gen">
                            {!! Form::open(['route' => ['autorizaciones.responder',$quotation], 'method' => 'PUT', 'files' => 'false']) !!}
                                {{ csrf_field() }}
                                <input type="hidden" name="respuesta" value="5">
                                {{ Form::button('<i class="fas fa-times"></i> Rechazar', ['type' => 'submit', 'class'=> 'btn  tools-menu-btn buttons-accept pull-right lavared-bg white-text' ]) }}
                            {!! Form::close() !!}
                            </div>
                        </div>
                        <div class="quot-send-btn">
                            <div class="tools-btns-gen">
                            {!! Form::open(['route' => ['autorizaciones.responder',$quotation], 'method' => 'PUT', 'files' => 'false']) !!}
                                {{ csrf_field() }}
                                <input type="hidden" name="respuesta" value="4">
                                {{ Form::button('<i class="fas fa-check"></i> Aprobar', ['type' => 'submit', 'class'=> 'btn  tools-menu-btn buttons-accept pull-right white-text btn-approved' ]) }}
                            {!! Form::close() !!}
                            </div>
                        </div>
                        @endcan
                </div>


            </div>

        </section>

    </div>
</div>
<!-- /.modal -->
<!-- /.content -->
@endsection

@section('pagescript')
<script src="{{ asset('js/api.js') }}"></script>
<script>
    $("#file-1").fileinput({
        allowedFileExtensions: ['jpg', 'png', 'gif','doc','pdf','xls','docx','xlsm'],
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
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });
</script>
@endsection
