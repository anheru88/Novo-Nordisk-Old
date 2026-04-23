@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / Autorizaciones
            </div>
            <h1>
                Autorizaciones pendientes
            </h1>
        </section>
        <section class="content">
            <!-- Cotizaciones -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fas fa-file-alt"></i> Cotizaciones</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="quotations" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <span class="hidden-md hidden-lg">#</span>
                                        <span class="hidden-xs hidden-sm">Cotización #</span>
                                    </th>
                                    <th>Cliente</th>
                                    <th>Canal</th>
                                    <th>CAM</th>
                                    <th>Vigencia desde</th>
                                    <th>Vigencia hasta</th>
                                    <th>Estado</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($quotations->count())
                                @foreach($quotations as $quotation)
                                <tr>
                                    <td><a
                                            href="{{ route('autorizaciones.cotizacion',['id' => $quotation->id_quotation ]) }}">{{
                                            $quotation->quota_consecutive }}</a>
                                    </td>
                                    <td><a
                                            href="{{ route('autorizaciones.cotizacion',['id' => $quotation->id_quotation ]) }}">{{
                                            $quotation->cliente->client_name }}</a>
                                    </td>
                                    <td>{{ $quotation->channel->channel_name }}</td>
                                    <td>{{ $quotation->creator->name }}</td>
                                    <td><i class="fas fa-calendar-alt"></i>
                                        {{ date('d-m-Y',strtotime($quotation->quota_date_ini)) }}</td>
                                    <td><i class="fas fa-calendar-alt"></i>
                                        {{ date('d-m-Y',strtotime($quotation->quota_date_end)) }}</td>
                                    <td>
                                        @if ($quotation->status_id > 0)
                                        <div class="label"
                                            style="background-color:{{ $quotation->status->status_color }}; font-size: 11px; border-radius: 3px;}">
                                            {!! $quotation->status->status_symbol !!} {{ $quotation->status->status_name
                                            }}
                                        </div>
                                        @else
                                        {!! statusCot($quotation->is_authorized, $quotation->pre_aproved) !!}
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-info"
                                            onclick="window.location.href='{{ route('autorizaciones.cotizacion', ['id' => $quotation->id_quotation]) }}'">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>No hay cotizaciones por aprobar</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>

            <!-- Negociaciones -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fas fa-briefcase"></i> Negociaciones</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="negotiations" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <span class="hidden-md hidden-lg">#</span>
                                        <span class="hidden-xs hidden-sm">Negociación #</span>
                                    </th>
                                    <th>Cliente</th>
                                    <th>Canal</th>
                                    <th>CAM</th>
                                    <th>Vigencia desde</th>
                                    <th>Vigencia hasta</th>
                                    <th>Estado</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($negotiations->count())
                                @foreach($negotiations as $negotiation)
                                <tr>
                                    <td><a
                                            href="{{ route('autorizaciones.negociacion',['id' => $negotiation->id_negotiation ]) }}">{{
                                            $negotiation->negotiation_consecutive }}</a>
                                    </td>
                                    <td><a
                                            href="{{ route('autorizaciones.negociacion',['id' => $negotiation->id_negotiation ]) }}">{{
                                            $negotiation->cliente->client_name }}</a>
                                    </td>
                                    <td>{{ $negotiation->channel->channel_name }}</td>
                                    <td>{{ $negotiation->creator->name }}</td>
                                    <td><i class="fas fa-calendar-alt"></i>
                                        {{ date('d-m-Y',strtotime($negotiation->negotiation_date_ini)) }}</td>
                                    <td><i class="fas fa-calendar-alt"></i>
                                        {{ date('d-m-Y',strtotime($negotiation->negotiation_date_end)) }}</td>
                                    <td>
                                        {!! statusCot($negotiation->is_authorized, $negotiation->pre_aproved) !!}
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-info"
                                            onclick="window.location.href='{{ route('autorizaciones.negociacion', ['id' => $negotiation->id_negotiation]) }}'">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>No hay negociaciones por aprobar</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>

            <!-- Productos -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fas fa-dollar-sign"></i> Lista de precios</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="datatable_full" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Version de lista</th>
                                    <th>Subida el </th>
                                    <th>Estado</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($pricelists->count())
                                @foreach($pricelists as $key => $pricelist)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><a
                                            href="{{ route('autorizaciones.lista',['id' => $pricelist->id_pricelists ]) }}">{{
                                            $pricelist->list_name}}</a>
                                    </td>
                                    <td><i class="fas fa-calendar-alt"></i>
                                        {{ date('d-m-Y',strtotime($pricelist->created_at)) }}</td>
                                    <td>
                                        {!! statusList($pricelist->active) !!}
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-info">
                                            <a
                                                href="{{ route('autorizaciones.lista', ['id' => $pricelist->id_pricelists]) }}"><i
                                                    class="fas fa-search"></i></a>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>No hay listas por aprobar</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
    </div>
</div>
<!-- /.content -->
@endsection
@section('pagescript')
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script>
    $(document).ready(function() {
        $('#negotiations').DataTable( {
            'language': {
                "url":  "{{ asset('lang/es/datatable.es.lang') }}",
            },
        } );
        $('#quotations').DataTable( {
            'language': {
                "url":  "{{ asset('lang/es/datatable.es.lang') }}",
            },
        } );
    } );
</script>
@endsection
