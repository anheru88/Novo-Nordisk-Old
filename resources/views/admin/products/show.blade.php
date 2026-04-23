@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header">
        <div class="bread-crumb">
            <a href="#">Inicio</a> / <a href="{{ route('products.index') }}"> Productos </a> / {{ $product->prod_name }}
        </div>
        <div class="tools-header">
            @can('products.edit')
            <div class="tools-menu-btn darkblue-bg white-text">
                <div class="tools-menu-btn-icon"><i class="fa fa-edit"></i></div>
                <div class="tools-menu-btn-text" data-toggle="modal"><a href="{{ route('products.edit', ['product' => $product->id_product]) }}">Modificar</a></div>
            </div>
            @endcan
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-info quot">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="quot-number">
                    <h1>{{ $product->prod_name }}</h1>
                </div>
                <div class="content-divider"></div>
                <div class="container-fixed">
                    <div class="row quot-first-data">
                        <div class="col-xs-12 col-sm-12 no-padding">
                            <div class="col-xs-12 col-sm-4 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Nombre comercial</div>
                                    <div class="quot-data-box-content-show">{{ $product->prod_commercial_name }}</div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Nombre Generico</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->prod_generic_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Vigencia desde</div>
                                    <div class="quot-data-box-content-show">
                                        <i class="fa fa-calendar"></i> {{ date('d-m-Y',strtotime($product->prod_valid_date_ini)) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Vigencia hasta</div>
                                    <div class="quot-data-box-content-show">
                                        <i class="fa fa-calendar"></i> {{ date('d-m-Y',strtotime($product->prod_valid_date_end)) }}
                                    </div>
                                </div>
                            </div>
                            <div class="content-divider"></div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title"> Código SAP</div>
                                    <div class="quot-data-box-content-show">
                                        {!! $sap !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Registro Invima</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->prod_invima_reg }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title"> Código CUM</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->prod_cum }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Unidades de presentación</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->prod_package_unit }}
                                    </div>
                                </div>
                            </div>
                            <div class="content-divider"></div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Unidad minima</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $measure_unit->unit_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Linea</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product_lines->line_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Medicamento en control</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->is_prod_regulated }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Marca (Brand)</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->brand->brand_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Código IUM</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->prod_cod_IUM }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Código ATC</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->prod_cod_ATC }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Código EAN (código de barras)</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->prod_cod_EAN }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 no-padding">
                                <div class="quot-data-box-auto">
                                    <div class="quot-data-box-title">Concentracion del medicamento</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->prod_concentration }}
                                    </div>
                                </div>
                            </div>
                            <div class="content-divider"></div>
                            <div class="col-sm-12 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Presentación del producto</div>
                                    <div class="quot-data-box-content-show">
                                        {{ $product->prod_package }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12  no-padding">
                            @foreach ($product_prices as $key => $price)
                            <div class="row quot-first-data">
                                    <div class="col-xs-12 col-sm-2 no-padding">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Vigencia Precio - desde</div>
                                            <div class="quot-data-box-content-small contact-gray-box">
                                                <div class="email">
                                                    <i class="fa fa-calendar"></i> {{ date('d-m-Y',strtotime($price->prod_valid_date_ini)) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-2 no-padding">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Vigencia Precio - hasta</div>
                                            <div class="quot-data-box-content-small contact-gray-box">
                                                <div class="email">
                                                    <i class="fa fa-calendar"></i> {{ date('d-m-Y',strtotime($price->prod_valid_date_end)) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 no-padding">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-icon-mini"><i class="fa fa-dollar"></i></div>
                                            <div class="quot-data-box-title">Precio Vigente Comercial</div>
                                            <div class="quot-data-box-content-small contact-gray-box">
                                                <div class="email">
                                                    $ {{ number_format($price->v_commercial_price,0, ",", ".") }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 no-padding">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-icon-mini"><i class="fa fa-dollar"></i></div>
                                            <div class="quot-data-box-title">Precio Vigente Institucional</div>
                                            <div class="quot-data-box-content-small contact-gray-box">
                                                <div class="email">
                                                    $ {{ number_format($price->v_institutional_price,0, ",", ".") }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-2 no-padding">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-icon-mini"><i class="fa fa-dollar"></i></div>
                                            <div class="quot-data-box-title">Precio máximo regulado</div>
                                            <div class="quot-data-box-content-small contact-gray-box">
                                                <div class="email">
                                                    @if ($price->prod_increment_max != "N/A")
                                                    ${{ number_format($price->prod_increment_max,0, ",", ".") }}
                                                    @else
                                                    {{ $price->prod_increment_max }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.modal -->
<!-- /.content -->
@endsection
