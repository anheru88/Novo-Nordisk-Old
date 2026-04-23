@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="quotation">
        <section class="content-header">
            <div class="bread-crumb" v-on:load="quotaEdit(3)">
                <a href="#">Inicio</a> / Cotizaciones / Editar cotización - {{ $quotation->quota_consecutive }}

            </div>
            <div class="tools-header">
                {!! Form::open(['action' => ['QuotationsController@cancelQuota',$quotation->id_quotation],'method'=> 'PUT','files' =>
                    'false', 'id' => 'cancelquota']) !!}
                <div class="tools-menu-btn btn-red lavared-bg white-text">
                    <div class="tools-menu-btn-icon"><i class="fas fa-ban"></i></div>
                    <div class="tools-menu-btn-text" v-on:click="cancelQuota"> Anular cotización </div>
                </div>
                {!! Form::close() !!}
            </div>
        </section>
        <!-- Main content -->
        {!! Form::open(['action' => ['QuotationsController@update',$quotation->id_quotation],'method'=> 'PUT','files' =>
        'false']) !!}
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
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($quotation->quota_date_ini)) }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2">
                            <div class="quot-data-box-title">Vigente hasta</div>
                            <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                {{ date('d-m-Y', strtotime($quotation->quota_date_end)) }}
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
                                            <input type="hidden" id="idClient" name="id_client" value="{{ $quotation->cliente->id_client}}">
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
                                        <div class="quot-data-box-content">{{ $quotation->city->loc_name }}</div>
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
                                        <div class="quot-data-box-title">Estado de cotización</div>
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
                                    <div class="quot-data-box-precio" v-cloak>
                                        <input type="hidden" name="quota_value" id="quota_val" value="{{ $quotation->quota_value }}">
                                        $@{{ formatPrice(totalQuota) }}
                                        <input type="hidden" name="quota_value" id="quota_value" :value="totalQuota">
                                    </div>
                                </div>
                                <div class="quot-data-box level-gray-box">
                                    <div class="quot-auth-list">
                                        <div class="quot-auth-list-name">Nivel de autorización</div>
                                        <div class="quot-auth-list-number"><strong>@{{ levelAuthQuota }}</strong></div>
                                        <input type="hidden" id="id_auth_level" name="id_auth_level" :value="levelAuthQuota">
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
                                    @if ($quotation->comments != "")
                                        <div class="quot-data-box-title">Comentarios del CAM ({{ $quotation->creator->name }}):</div>
                                        <div class="form-group">{{ $quotation->comments }}</div>
                                    @endif
                                    @if ($quotation->comments_pre != "")
                                        <div class="quot-data-box-title">Comentarios del preautorizador:</div>
                                        <div class="form-group">{{ $quotation->comments_pre }}</div>
                                    @endif
                                    @if ($quotation->comments_auth != "")
                                        <div class="quot-data-box-title">Comentarios del autorizador:</div>
                                        <div class="form-group">{{ $quotation->comments_auth }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 no-padding">
                                <div class="quot-data-box gray-box">
                                    <div class="quot-data-box-title">Documentos de soporte:</div>
                                    <div class="form-group">
                                        @foreach ($quotation->docs as $doc)
                                        <div class="doc-quota">
                                            <a href="{{ asset($doc->file_folder.'/'.$doc->doc_name) }}"><i class="fas fa-file-alt"></i>  {{ $doc->doc_name }}</a>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <!-- /.Agregar productos -->
                    <div class="container-fixed">
                            <div class="row quot-add-product">
                                <div class="col-xs-12">
                                    <div class="quot-number">
                                        <h3><i class="ion ion-compose"></i> Añadir productos</h3>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-9">
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <div class="form-group"> <label>Producto</label></div>
                                        <div class="form-group">
                                            <div class="form-group">
                                                {!! Form::select('id_product',$productos, null,['class'=> 'form-control
                                                focus filter-table-textarea', 'placeholder' => 'Seleccione','id' => 'productos',
                                                'v-on:change' => 'getPreviousProduct']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <div class="form-group"> <label>Condición financiera</label></div>
                                        <div class="form-group">
                                                <div class=" pay-control">  {{ $quotation->cliente->payterm->payterm_name }}</div>
                                                {{ Form::hidden('id_payterm_name', $quotation->cliente->payterm->payterm_name, [ 'id'=>'id_payterm_name']) }}
                                                {{ Form::hidden('id_payterm', $quotation->quotadetails[0]->id_payterm, [ 'id'=>'id_payterm']) }}
                                                {{ Form::hidden('time_discount', $quotation->quotadetails[0]->time_discount, [ 'id'=>'time_discount']) }}
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3">
                                        <div class="form-group"> <label>Cantidad</label></div>
                                        <div class="form-group">
                                            {{ Form::number('quantity', 1, ['class' => 'form-control', 'id'=>'quantity','v-model'=>'quantity', 'v-on:blur' => 'calcProductQuota(false)']) }}
                                        </div>
                                        <div class="filter-quantity">
                                            <label class="checkbox-container" @change="modifyPrice()">Calcular en unidades de presentacion
                                                {{ Form::checkbox('show_min','1', null, ['class' => 'unidades','id'=>'unidades']) }}
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group"> <label v-if="quantCheck">Precio unidad mínima</label> <label v-else>Precio presentación</label></div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                {{ Form::number('precio', null, ['class' => 'form-control', 'id'=>'precio','v-model'=>'productPrice', 'v-on:blur' => 'calcProductQuota(false)']) }}
                                                {{ Form::hidden('precio_lista', null, ['class' => 'form-control', 'id'=>'precio_lista','v-model'=>'productPriceOld']) }}
                                                {{ Form::hidden('unidad_minima', null, ['class' => 'form-control', 'id'=>'unidad_minima','v-model'=>'productPriceUnits']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-sm-4" v-if="hastaProd">
                                        <div class="form-group"><label>Ultimo precio cotizado</label></div>
                                        <div class="form-group">
                                            <div class="prev-price">
                                                <div class="price"><strong>Precio:</strong> $@{{ formatPrice(productPriceOld) }}</div>
                                                <div class="vigencia"><strong>Vigencia desde:</strong> @{{ desdeProd }}</div>
                                                <div class="vigencia"><strong>Vigencia hasta:</strong> @{{ hastaProd }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3 no-padding">
                                    <div class="col-xs-12">
                                        <div class="quot-discounts">
                                            <div class="quot-discount-list">
                                                <div class="quot-discount-list-name">Descuento por pronto pago</div>
                                                <div class="quot-discount-list-number">@{{ percentDiscount }}%</div>
                                            </div>
                                            <div class="quot-discount-list">
                                                <div class="quot-discount-list-name">@{{ textDescription }} aplicado al precio</div>
                                                <div class="quot-discount-list-number">@{{ priceDiscount }}%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="quot-auth">
                                            <div class="quot-auth-list">
                                                <div class="quot-auth-list-name">Requiere autorización nivel</div>
                                                <div class="quot-auth-list-number">
                                                    @{{ levelAuth }}
                                                    <input type="hidden" :value="levelDescID">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" >
                                    <div class="form-group">
                                        {{ Form::button('<i class="fas fa-plus"></i> Agregar producto', ['type' => 'button', 'class' => 'btn btn-bluegen btn-sm pull-right', 'v-on:click' => 'calcProductQuota(true)'] ) }}
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
                                <table id="quotation_edit" data-pagination="true" data-search="true" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">Producto</th>
                                            <th data-sortable="true" class="text-center">CANTIDAD</th>
                                            <th data-sortable="true" class="text-center">PRECIO UNIDAD MÍNIMA</th>
                                            <th data-sortable="true" class="text-center">PRECIO PRESENTACIÓN COMERCIAL</th>
                                            <th data-sortable="true" class="text-center">PRECIO TOTAL COTIZADO</th>
                                            <th data-sortable="true" class="text-center">AJUSTES AL PRECIO</th>
                                            <!-- <th data-sortable="true">Dto. Comercial</th>-->
                                            <th data-sortable="true" class="text-center">CONDICIONES FINANCIERAS</th>
                                            <th data-sortable="true" class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appRows">
                                        <tr v-for="(input, index) in inputProducts" :id="'prod'+input.productId"
                                            class="animated" :class="input.isValid == 0 ? 'bg-red' : ''">
                                            <td>@{{ input.productname }}<input type="hidden" name="id_product[]" :value="input.productId"></td>
                                            <td class="text-center">
                                                @{{ input.quantity }}<input type="hidden" name="quantity[]" :value="input.quantity">
                                            </td>
                                            <td class="text-center">
                                                $@{{ formatPrice(input.uMinima) }}
                                                <input type="hidden" name="prod_uminima[]" :value="input.uMinima">
                                            </td>
                                            <td class="text-center">
                                                $@{{ formatPrice(input.vComercial) }}
                                                <input type="hidden" name="prod_cost[]" :value="input.vComercial">
                                            </td>
                                            <td class="text-center">
                                                $@{{ formatPrice(input.vTotal) }}
                                                <input type="hidden" name="id_total_product[]" :value="input.vTotal">
                                            </td>
                                            <td class="text-center">
                                                @{{ input.symbol }}@{{ input.dtoPrecio }}%
                                                <input type="hidden" name="pay_discount[]" :value="input.symbol+input.dtoPrecio">
                                                <input type="hidden" name="id_prod_auth_level[]" :value="input.productAuthLevel">
                                                <input type="hidden" name="authlevel[]" :value="input.productLevel">
                                            </td>
                                            <td class="text-center">
                                                @{{ input.fPago }}
                                                <input type="hidden" name="pay_term[]" :value="input.fPagoID">
                                                <input type="hidden" name="percent_discount[]" :value="input.percent">
                                            </td>
                                            <td>
                                                <a class="btn btn-xs btn-danger" v-on:click="removeProduct(index)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.Lista de productos -->
                </div>
                <div class="content-divider"></div>
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
                                <div class="quot-data-box-title">Agregar nuevo comentario</div>
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
                    <div class="col-xs-12 col-sm-12 ">
                        <div class="quot-number">
                            <h3><i class="fas fa-barcode"></i> Documentos adjuntos</h3></span>
                        </div>
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Seleccione los documentos que desea adjuntar a la
                                cotización <br></div>
                            <div class="quot-data-box-content">
                                <div class="file-loading">
                                    {!! Form::file ('docs[]', ['class'=>'file', 'type'=>'file', 'id'=>'file-1'
                                    ,'multiple']) !!}
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
<script src="{{ asset('js/quotation_edit.js') }}"></script>
<script>

$('#file-1').fileinput({
        allowedFileExtensions: ['jpg', 'png', 'gif','doc','pdf','xls','docx','xlsx'],
        browseClass: "btn btn-primary btn-block",
        maxFileSize: 4000,
        maxFileCount: 10,
        showUpload: false,
        showRemove: false,
        showCaption: false,
        browseOnZoneClick: true,
        showBrowse: false,
        showDrag:true,
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });

</script>
@endsection
