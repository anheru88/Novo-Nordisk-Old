@extends('admin.layout')
@section('content')
<div class="content-wrapper" id="app">
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
                    {!! Form::open(['route' => ['autorizaciones.cotizacion',$quotation], 'method' => 'PUT', 'files' =>
                    'true', 'v-on:submit' => 'preQuota', 'ref'=>'preQuota', 'novalidate']) !!}
                    <div class="quot-number">
                        <div class="col-xs-6">
                            <h1>Cotización - {{ $quotation->quota_consecutive }}</h1>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Fecha de creación</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>{{ date('Y-m-d',
                                strtotime($quotation->created_at )) }}
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente desde</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>{{ date('Y-m-d',
                                strtotime($quotation->quota_date_ini))}}
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="quot-data-box-title">Vigente hasta</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>{{ date('Y-m-d',
                                strtotime($quotation->quota_date_end))}}
                            </div>
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
                                        <div class="quot-data-box-content">{{ $quotation->cliente->client_sap_code }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Ciudad</div>
                                        <div class="quot-data-box-content">{{ $quotation->city->loc_name }}</div>
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
                                                {!! Form::select('id_authorizer[]',$autorizing, null,['class'=>
                                                'ui multiple fluid search selection dropdown uppercase select-multiple', 'placeholder' =>
                                                'Seleccione','id' => 'autorizadores','required','multiple']) !!}
                                                <input type="hidden" name="doc_lvl" id="doc_lvl" value="{{ $quotation->id_auth_level }}">
                                                <input type="hidden" name="type" value="pre">
                                                <input type="hidden" name="respuesta" :value="approved">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Estado</div>
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

                            </div>
                            <div class="col-xs-12 col-sm-3 no-padding">
                                <div class="quot-data-box gray-box">
                                    <div class="quot-data-box-title">Valor de la cotización</div>
                                    <div class="quot-data-box-precio">
                                        ${{ number_format( $quotation->quota_value,0, ",", ".") }}</div>
                                </div>
                                <div class="quot-data-box level-gray-box">
                                    <div class="quot-auth-list">
                                        <div class="quot-auth-list-name">Nivel de autorización</div>
                                        <div class="quot-auth-list-number"><strong>{!! $quotation->id_auth_level
                                                !!}</strong></div>
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
                                        @foreach ($quotation->docs as $doc)
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
                                <table data-toggle="table" data-pagination="true" data-search="true"
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($quotation->quotadetails->count())
                                        @foreach($quotation->quotadetails as $product)
                                        <tr>
                                            <td><a
                                                    href="{{ route('products.show',['product' => $product->id_quota_det]) }}">{{
                                                    $product->product->prod_name }}</a>
                                            </td>
                                            <td class="text-center">{{ $product->quantity }}</td>
                                            <td class="text-center">
                                                ${{ number_format( $product->price_uminima,0, ",", ".") }}</td>
                                            <td class="text-center">
                                                ${{ number_format( $product->prod_cost,0, ",", ".") }}</td>
                                            <td class="text-center">
                                                ${{ number_format( $product->totalValue,0, ",", ".") }}</td>
                                            <td class="text-center">{{ $product->pay_discount }}%</td>
                                            <td class="text-center">{{ $product->payterm->payterm_name }}</td>
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
                        <section>
                            <!-- row -->
                            <div class="row quot-add-product">
                                <div class="col-xs-12">
                                    <div class="quot-number">
                                        <h3><i class="fa fa-barcode"></i> Comentarios</h3>
                                    </div>
                                </div>
                                @if (sizeof($quotation->usercomments) > 0)
                                <div class="col-md-12">
                                    <ul class="timeline">
                                        @foreach ($quotation->usercomments as $comment)
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
                                <div class="col-xs-12 col-sm-12">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Agregar nuevo comentarios</div>
                                        <div class="quot-data-box-content">
                                            <div class="input-group">
                                                {{ Form::textarea('comment', null, ['class' => 'form-control',
                                                'id'=>'comment'])}}
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
                            <div class="col-xs-12 col-sm-12 ">
                                <div class="col-sm-12 no-padding">
                                    <div class="quot-data-box">
                                        <div class="quot-data-box-title">Seleccione los documentos que desea adjuntar a
                                            la preaprobación</div>
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
                        </section>
                    </div>
                    <!-- /.Lista de productos -->
                    @can('preautorizacion')
                    <div class="quot-send-btn-accept">
                        <div class="tools-btns-gen">
                            {{ Form::button('<i class="fas fa-paper-plane"></i> Enviar a autorizador', ['type' =>
                            'submit', 'class'=> 'btn btn-basic tools-menu-btn buttons-accept pull-right white-text btn-approved', 'v-on:click' => 'approved = 3' ]) }}
                        </div>
                    </div>
                    <div class="quot-send-btn-reject">
                        <div class="tools-btns-gen">
                            {{ Form::button('<i class="fas fa-times"></i> Rechazar', ['type' => 'submit', 'class'=> 'btn
                            tools-menu-btn buttons-accept pull-right lavared-bg white-text', 'v-on:click' => 'approved = 7' ]) }}
                        </div>
                    </div>
                    <div class="quot-send-btn-return">
                        <div class="tools-btns-gen">
                            {{ Form::button('<i class="fas fa-reply"></i> Solicitar correcciones', ['type' => 'submit', 'class'=> 'btn
                            tools-menu-btn buttons-accept pull-right white-text btn-returned', 'v-on:click' => 'approved = 2'            ]) }}
                        </div>
                    </div>
                    @endcan
                </div>
            </div>
            {!! Form::close() !!}
        </section>
    </div>
</div>
<!-- /.modal -->
<!-- /.content -->
@endsection

@section('pagescript')
<script src="{{ asset('js/utilities.js') }}"></script>
<script>
    $('#file-1').fileinput({
        allowedFileExtensions: ['jpg', 'png', 'gif','doc','pdf','xls','docx','xlsx'],
        browseClass: "btn btn-primary btn-block",
        browseOnZoneClick: true,
        maxFileSize: 4000,
        maxFileCount: 10,
        showUpload: false,
        showRemove: false,
        showCaption: false,
        showDrag:true,
        showBrowse: false,
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });

    $(document).ready(function() {
        $('.ui.selection.dropdown').dropdown();
    });

</script>
@endsection
