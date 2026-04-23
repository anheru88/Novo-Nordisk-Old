@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="#">Home</a> / Autorizaciones / Lista de precios
            </div>
            <h1>
               Lista de precios #{{ strtoupper($pricelist->list_name) }} | Creada el {{ date('d-m-Y',strtotime($pricelist->created_at)) }}
            </h1>
            <div class="tools-header">
                @can('autorizaciones.show')
                <div class="tools-btns-gen ">
                    {!! Form::open(['route' => 'lista.aprobada', 'method' => 'POST', '@submit' => 'acceptform' , 'ref'=>'approved']) !!}
                    {{ Form::hidden('id_pricelists', $pricelist->id_pricelists, ['class' => 'form-control', 'id'=>'id_pricelists']) }}
                    <button class="btn btn-sm btn-success" type="submit"> <i class="fas fa-check"></i> Aprobar</button>
                    {!! Form::close() !!}
                </div>
                <div class="tools-btns-gen ">
                    {!! Form::open(['route' => 'lista.rechazada', 'method' => 'POST', '@submit' => 'rejectform', 'ref'=>'rejected']) !!}
                    {{ Form::hidden('id_pricelists', $pricelist->id_pricelists, ['class' => 'form-control', 'id'=>'id_pricelists']) }}
                    {{ Form::hidden('comments', null, ['class' => 'form-control', 'id'=>'answer','v-model' => 'answer']) }}
                    <button class="btn btn-sm btn-danger" type="submit"><i class="far fa-times-circle"></i> Rechazar</button>
                    {!! Form::close() !!}
                </div>
                @endcan
            </div>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">Institucionales</h2>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body ">
                    <div class="container-fixed table-responsive">
                        <table id="informe1" class="table table-striped ">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Precio Institucional</th>
                                    <th class="text-center">Precio Máximo</th>
                                    @if(sizeof($pricelistinstitucional) > 0)
                                    @for($i = 1; $i < sizeof($pricelistinstitucional[0]); $i++) <th width="12%"
                                        class="text-center">Descuento Nivel {{ $i + 1 }}</th>
                                        @endfor
                                        @endif
                                        <th width="10%" class="text-center">Vigencia desde</th>
                                        <th width="10%" class="text-center">Vigencia hasta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($productos->count())
                                @foreach($productos as $key => $producto)
                                <tr>
                                    <td><a href="{{ route('products.show',['product' => $producto->product->id_product]) }}">{{ $producto->product->prod_name }}</a></td>
                                    <td class="text-center"> ${{ number_format($producto->v_institutional_price,0, ",", ".") }}</td>
                                    <td class="text-center">
                                        @if ($producto->prod_increment_max != "N/A")
                                        ${{ number_format($producto->prod_increment_max,0, ",", ".") }}
                                        @else
                                        {{ $producto->prod_increment_max }}
                                        @endif
                                    </td>
                                    @for($i = 1; $i < sizeof($pricelistinstitucional[$key]); $i++)
                                    <td>
                                        <div class="price">
                                            ${{ number_format($pricelistinstitucional[$key][$i]->discount_price,0, ",", ".") }}
                                            <label class="label label-info pull-right">{{ $pricelistinstitucional[$key][$i]->discount_value }}%</label>
                                        </div>
                                    </td>
                                        @endfor
                                    <td class="text-center"><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y',strtotime($producto->prod_valid_date_ini)) }}</td>
                                    <td class="text-center"><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y',strtotime($producto->prod_valid_date_end)) }}</td>
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
                    <!-- /.Lista de productos -->
                </div>
            </div>

            <div class="box box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">Comerciales</h2>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body ">
                    <div class="container-fixed table-responsive">
                        <table id="informe2" class="table table-striped ">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Precio Comercial</th>
                                    <th class="text-center">Precio Máximo</th>
                                    @if(sizeof($pricelistcomercial) > 0)
                                    @for($i = 1; $i < sizeof($pricelistcomercial[0]); $i++) <th width="12%"
                                        class="text-center">Descuento Nivel {{ $i + 1 }}</th>
                                        @endfor
                                        @endif
                                        <th width="10%" class="text-center">Vigencia desde</th>
                                        <th width="10%" class="text-center">Vigencia hasta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($productos->count())
                                @foreach($productos as $key => $producto)
                                <tr>
                                    <td><a href="{{ route('products.show',['product' => $producto->product->id_product]) }}">{{ $producto->product->prod_name }}</a></td>
                                    <td class="text-center">${{ number_format($producto->v_commercial_price,0, ",", ".") }}</td>
                                    <td class="text-center">
                                        @if ($producto->prod_increment_max != "N/A")
                                        ${{ number_format($producto->prod_increment_max,0, ",", ".") }}
                                        @else
                                        {{ $producto->prod_increment_max }}
                                        @endif
                                    </td>
                                    @for($i = 1; $i < sizeof($pricelistcomercial[$key]); $i++)
                                    <td>
                                        <div class="price">
                                            ${{ number_format($pricelistcomercial[$key][$i]->discount_price,0, ",", ".") }}
                                            <label class="label label-info pull-right">{{ $pricelistcomercial[$key][$i]->discount_value }}%</label>
                                        </div>
                                    </td>
                                    @endfor
                                    <td class="text-center"><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y',strtotime($producto->prod_valid_date_ini)) }}</td>
                                    <td class="text-center"><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y',strtotime($producto->prod_valid_date_end)) }}</td>
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
                    <!-- /.Lista de productos -->
                </div>
            </div>
        </section>

        <!-- /.modal -->
<div class="modal fade" id="modal-rejected">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['route' => 'lista.rechazada', 'method' => 'POST']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="ion ion-ios-search-strong"></i> Rechazar lista de precios</h3>
            </div>
            <div class="modal-body">
                <div class="quot-data-box-title">Escriba la razón de rechazo de la lista de precios</div>
                <div class="quot-data-box-content">
                    {{ Form::textarea('comments', null, ['class' => 'form-control', 'id'=>'comments','rows'=>'4','required'])}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default btn-novo pull-right">Enviar</button>
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
<script src="{{ asset('js/utilities.js') }}" ></script>
<script>
    $(document).ready(function() {
        $('#informe1').DataTable( {
            responsive: true,
            'language': {
                "url":  "{{ asset('lang/es/datatable.es.lang') }}",
            },
        });
        $('#informe2').DataTable( {
            responsive: true,
            'language': {
                "url":  "{{ asset('lang/es/datatable.es.lang') }}",
            },
        });
    });
</script>
@endsection