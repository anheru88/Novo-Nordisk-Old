@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app" v-cloak>
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / <a
                    href="{{  route('cotizaciones.index') }}">Cotizaciones</a> / Crear Cotización
            </div>
            <h1>
                Crear nueva cotización
            </h1>
        </section>
        <!-- Main content -->
        {!! Form::open(['action' => 'QuotationsController@store','method'=> 'POST', 'files' => 'true', 'v-on:submit' =>
        'sendCotizacion']) !!}
        <section class="content">
            <div class="box box-info quot">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="container-fixed">
                        <div class="row quot-first-data">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="date-col-1">
                                    <div class="form-group">
                                        <label>Vigente desde</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            {{ Form::date('quota_date_ini', null,
                                            array('required','id'=>'quota_date_ini','class' => 'form-control')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="date-col-2">
                                    <div class="form-group">
                                        <label></label>
                                        <div class="input-group">
                                            <label class="checkbox-container">Hoy
                                                <input id="today" type="checkbox" value="" @change="setDate()">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="date-col-3">
                                    <div class="form-group">
                                        <label>Vigente hasta</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            {{ Form::date('quota_date_end', null, array('required', 'class' =>
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
                                <div class="alertdate" v-if="showDaysWarning">
                                    <i class="fas fa-exclamation-circle"></i> <strong> Alerta:</strong> La cotización
                                    excede los 365 dias.
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2" v-if="showTotalCot">
                                <div class="form-group text-center">
                                    <label>Valor de la cotización</label>
                                    <div class="input-group">
                                        <h2 id="cod_cliente">$@{{ formatPrice(totalQuota) }}</h2>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2" v-if="levelAuthQuota >= 2">
                                <div class="form-group text-center">
                                    <label>Nivel de autorización</label>
                                    <div class="input-group">
                                        <h2 id="cod_cliente">@{{ levelAuthQuota }}</h2>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <input type="hidden" name="quota_value" id="quota_value" :value="totalQuota">
                            <input type="hidden" id="id_auth_level" name="id_auth_level" :value="levelAuthQuota">
                        </div>
                        <div class="content-divider"></div>
                        <div class="container-fixed">
                            <div class="row quot-first-data">
                                <div class="col-xs-12 col-sm-12 col-md-5">
                                    <div class="form-group">
                                        <label>Seleccione un cliente de la lista</label>
                                        {!! Form::select('id_client',$clientes, null,['class'=> 'clientes-select
                                        form-control focus filter-table-textarea', 'placeholder' => 'Seleccione',
                                        'required','v-on:change'=>'getClient', 'v-model' => 'client', 'id' =>
                                        'id_client']) !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-2">
                                    <div class="form-group" v-if="clients">
                                        <label> Código SAP</label>
                                        <input type="text" name="client_sap_code" id="client_sap_code"
                                            :value="clientCode" class="form-control focus filter-table-textarea"
                                            disabled>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-2">
                                    <div class="form-group">
                                        <label>Canal</label>
                                        <input type="text" name="channel_name" id="channel_name" :value="clientChannel"
                                            class="form-control focus filter-table-textarea" disabled>
                                        <input type="hidden" name="id_client_channel" id="id_client_channel"
                                            :value="clientChannelID">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="form-group">
                                        <label>Ciudad</label>
                                        <input type="text" name="city" id="city" :value="city"
                                            class="form-control focus filter-table-textarea" disabled>
                                        {{ Form::hidden('id_city', null, [ 'id'=>'id_city','v-model'=>'selectedCity'])
                                        }}
                                    </div>
                                </div>
                                @if ($rol == "admin_venta")
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Asignar CAM a la cotización</label>
                                        <select id="id_cam"
                                            class=" clientes-select form-control focus filter-table-textarea"
                                            name="id_cam" required>
                                            <option value="">Seleccionar...</option>
                                            @if (sizeof($usuarios) > 0)
                                            @foreach ($usuarios as $key => $usuario)
                                            @if ($usuario['name'] == "")
                                            <div class="alert alert-warning">
                                                <strong>¡Advertencia!</strong> No hay Contactos Disponibles.
                                            </div>
                                            @else
                                            <option value="{{ $usuario['id'] }}">{{ $usuario['name'] }}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="content-divider"></div>
                        <!-- /.Agregar productos -->
                        <div class="container-fixed" v-if="client">
                            <div class="row quot-add-product">
                                <div class="col-xs-12">
                                    <div class="quot-number">
                                        <h3><i class="ion ion-compose"></i> Añadir productos</h3>
                                    </div>
                                    <div class="quot-charge" v-if="client">
                                        <a class="btn btn-bluegen btn-sm" data-toggle="modal"
                                            data-target="#modal-masiva" v-on:click="uncheck"><i
                                                class="fas fa-barcode"></i> CARGA EN BLOQUE PRODUCTOS</a>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-9">
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <div class="form-group"> <label>Producto</label></div>
                                        <div class="form-group">
                                            <div class="form-group">
                                                {!! Form::select('id_product',$productos, null,['class'=>
                                                'productos-select form-control
                                                focus filter-table-textarea', 'placeholder' => 'Seleccione','id' =>
                                                'productos',
                                                'v-on:change' => 'getPreviousProduct']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <div class="form-group"> <label>Condición financiera</label></div>
                                        <div class="form-group">
                                            <div class=" pay-control"> @{{ payterm_name }}</div>
                                            {{ Form::hidden('id_payterm', null, [
                                            'id'=>'id_payterm','v-model'=>'payterm_id']) }}
                                            {{ Form::hidden('time_discount', null, [
                                            'id'=>'time_discount','v-model'=>'timeDiscount']) }}
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3">
                                        <div class="form-group"> <label>Cantidad</label></div>
                                        <div class="form-group">
                                            {{ Form::number('quantity', 1, ['class' => 'form-control',
                                            'id'=>'quantity','v-model'=>'quantity', 'v-on:blur' =>
                                            'calcProductQuota(false)']) }}
                                        </div>
                                        <div class="filter-quantity">
                                            <label class="checkbox-container" @change="modifyPrice()">Calcular en
                                                unidades de presentacion
                                                {{ Form::checkbox('show_min','1', null, ['class' =>
                                                'unidades','id'=>'unidades']) }}
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group"> <label v-if="quantCheck">Precio unidad mínima</label>
                                            <label v-else>Precio presentación</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                {{ Form::number('precio', null, ['class' => 'form-control',
                                                'id'=>'precio','v-model'=>'productPrice', 'v-on:change' =>
                                                'calcProductQuota(false)']) }}
                                                {{ Form::hidden('precio_lista', null, ['class' => 'form-control',
                                                'id'=>'precio_lista','v-model'=>'productPriceOld']) }}
                                                {{ Form::hidden('unidad_minima', null, ['class' => 'form-control',
                                                'id'=>'unidad_minima','v-model'=>'productPriceUnits']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-sm-4" v-if="hastaProd">
                                        <div class="form-group"><label>Ultimo precio cotizado</label></div>
                                        <div class="form-group">
                                            <div class="prev-price">
                                                <div class="price"><strong>Precio:</strong>
                                                    $@{{ formatPrice(productPriceOld) }}</div>
                                                <div class="vigencia"><strong>Vigencia desde:</strong> @{{ desdeProd }}
                                                </div>
                                                <div class="vigencia"><strong>Vigencia hasta:</strong> @{{ hastaProd }}
                                                </div>
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
                                                <div class="quot-discount-list-name">@{{ textDescription }} aplicado al
                                                    precio</div>
                                                <div class="quot-discount-list-number">@{{ priceDiscount }}%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="quot-auth">
                                            <div class="quot-auth-list">
                                                <div class="quot-auth-list-name">Requiere autorización nivel</div>
                                                <div class="quot-auth-list-number"> @{{ levelAuth }}
                                                    <input type="hidden" :value="levelDescID">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        {{ Form::button('<i class="fas fa-plus"></i> Agregar producto', ['type' =>
                                        'button', 'class' => 'btn btn-bluegen btn-sm pull-right', 'v-on:click' =>
                                        'calcProductQuota(true)'] ) }}
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
                                        <h3><i class="fas fa-barcode"></i> Resumen de productos cotizados</h3></span>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="table-responsive">
                                        <table data-pagination="true" data-search="true"
                                            class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true">PRODUCTO</th>
                                                    <th data-sortable="true" class="text-center">CANTIDAD</th>
                                                    <th data-sortable="true" class="text-center">PRECIO UNIDAD MÍNIMA
                                                    </th>
                                                    <th data-sortable="true" class="text-center">PRECIO PRESENTACIÓN
                                                        COMERCIAL</th>
                                                    <th data-sortable="true" class="text-center">PRECIO TOTAL COTIZADO
                                                    </th>
                                                    <th data-sortable="true" class="text-center">AJUSTES AL PRECIO</th>
                                                    <!-- <th data-sortable="true">Dto. Comercial</th>-->
                                                    <th data-sortable="true" class="text-center">CONDICIONES FINANCIERAS
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="appRows">
                                                <tr v-for="(input, index) in inputProducts" :id="'prod'+input.productId"
                                                    class="animated">
                                                    <td>@{{ input.productname }}<input type="hidden" name="id_product[]"
                                                            :value="input.productId"></td>
                                                    <td class="text-center">
                                                        @{{ input.quantity }}<input type="hidden" name="quantity[]"
                                                            :value="input.quantity">
                                                    </td>
                                                    <td class="text-center">
                                                        $@{{ formatPrice(input.uMinima) }}
                                                        <input type="hidden" name="prod_uminima[]"
                                                            :value="input.uMinima">
                                                    </td>
                                                    <td class="text-center">
                                                        $@{{ formatPrice(input.vComercial) }}
                                                        <input type="hidden" name="prod_cost[]"
                                                            :value="input.vComercial">
                                                    </td>
                                                    <td class="text-center">
                                                        $@{{ formatPrice(input.vTotal) }}
                                                        <input type="hidden" name="id_total_product[]"
                                                            :value="input.vTotal">
                                                    </td>
                                                    <td class="text-center">
                                                        @{{ input.symbol }}@{{ input.dtoPrecio }}%
                                                        <input type="hidden" name="pay_discount[]"
                                                            :value="input.symbol+input.dtoPrecio">
                                                        <input type="hidden" name="id_prod_auth_level[]"
                                                            :value="input.productAuthLevel">
                                                        <input type="hidden" name="authlevel[]"
                                                            :value="input.productLevel">
                                                    </td>
                                                    <!-- <td>@{{ input.dtoComercial }}%<input type="hidden" name="commercial_discount[]" :value="input.dtoComercial"></td> -->
                                                    <td class="text-center">
                                                        @{{ input.fPago }}
                                                        <input type="hidden" name="percent_discount[]"
                                                            :value="input.percent">
                                                        <input type="hidden" name="pay_term[]" :value="input.fPagoID">
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-xs btn-danger"
                                                            v-on:click="removeProduct(index)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.Lista de productos -->
                    </div>
                    <div class="content-divider"></div>
                    <div class="col-xs-12 col-sm-12 ">
                        <div class="quot-number">
                            <h3><i class="fa fa-barcode"></i> Comentarios</h3>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Escriba los comentarios sobre la cotización</div>
                            <div class="quot-data-box-content">
                                <div class="input-group">
                                    {{ Form::textarea('comment', null, ['class' => 'form-control comment-area',
                                    'id'=>'comment'])}}
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
                        {{ Form::button('Guardar cotización', ['type' => 'submit', 'class' => 'btn btn-novo-big'] ) }}
                    </div>
        </section>
        {!! Form::close() !!}
        <div class="modal fade" id="modal-masiva">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h2 class="modal-title"><i class="ion ion-android-attach"></i> Carga en bloque de productos</h2>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 no-padding">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="select-all">
                                        <label class="checkbox-container">
                                            <input id="select_all" type="checkbox" @change="selectAllProducts">
                                            <span class="checkmark"></span>
                                            Seleccionar todos
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="table">
                                    <div class="modal-scroll">
                                        <table class="table no-margin table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="row">
                                                            <div class="col-xs-4">Producto</div>
                                                            <div class="col-xs-2 txt-center">Precio minimo nivel 3</div>
                                                            <div class="col-xs-3 txt-center">Precio cotizado actual
                                                            </div>
                                                            <div class="col-xs-3 txt-center">Precio de lista actual
                                                            </div>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="appRows">
                                                <tr v-for="(input, index) in allProducts" :id="'prod'+input.productId">
                                                    <td>
                                                        <div class="row">
                                                            <label class="checkbox-container">
                                                                <div class="col-xs-4">
                                                                    <input type="checkbox" name="prods[]" :value="index"
                                                                        @change="select" :id="input.productId">
                                                                    <span class="checkmark"></span>
                                                                    <strong> @{{ input.productname }}</strong>
                                                                </div>
                                                                <div class="col-xs-2 txt-center">
                                                                    $@{{ formatPrice(input.preciolvl3) }}</div>
                                                                <div class="col-xs-3 txt-center">
                                                                    $@{{ formatPrice(input.precioCotAct) }}</div>
                                                                <div class="col-xs-3 txt-center">
                                                                    $@{{ formatPrice( input.vComercial) }}</div>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-novo" data-dismiss="modal" @click="addAllProducts"><i
                                class="fa fa-barcode"></i> Cargar productos</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</div>
<!-- /.modal -->
<!-- /.content -->
@endsection

@section('pagescript')
<script src="{{ asset('js/quotation_create.js') }}"></script>
<script>
    $(document).ready(function() {
        $(window).keydown(function(event){

            if (event.which == 13) {
                event.stopPropagation();
            }
        });
        //Initialize Select2 Elements
        jQuery('.clientes-select').select2();
        jQuery('.clientes-select').on("change", function (e) {
            vueF.getClient();
        });
        jQuery('.productos-select').select2();
    });

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
