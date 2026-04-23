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
                Autorizaciones Aprobadas
            </h1>
            <div class="tools-header">
                <div class="tools-menu-btn">
                    <div class="tools-menu-btn-icon"><i class="ion ion-printer"></i></div>
                    <div class="tools-menu-btn-text"> Imprimir</div>
                </div>
                <div class="tools-menu-btn">
                    <div class="tools-menu-btn-icon"><i class="fas fa-file-excel"></i></div>
                    <div class="tools-menu-btn-text"><a href="{{ route('cotizaciones.create') }}">Listado en Excel</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="datatable_full" data-toggle="table" data-pagination="true" data-search="true" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th data-sortable="true">
                                        <span class="hidden-md hidden-lg">#</span>
                                        <span class="hidden-xs hidden-sm">Cotización #</span>
                                    </th>
                                    <th data-sortable="true">Cliente</th>
                                    <th data-sortable="true">Canal</th>
                                    <th data-sortable="true">Vigencia desde</th>
                                    <th data-sortable="true">Vigencia hasta</th>
                                    <th data-sortable="true">Estado</th>
                                    <th data-sortable="true" class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($quotations->count())
                                @foreach($quotations as $quotation)
                                <tr>
                                    <td><a href="{{ route('autorizaciones.cotizacion',['id' => $quotation->id_quotation ]) }}">{{ $quotation->id_quotation }}</a></td>
                                    <td><a href="{{ route('autorizaciones.cotizacion',['id' => $quotation->id_quotation ]) }}">{{ $quotation->cliente->client_name }}</a></td>
                                    <td>{{ $quotation->channel->channel_name }}</td>
                                    <td><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y',strtotime($quotation->quota_date_ini)) }}</td>
                                    <td><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y',strtotime($quotation->quota_date_end)) }}</td>
                                    <td>{!! statusCot($quotation->is_authorized) !!}</td>
                                    <td>
                                        @can('autorizaciones.show')
                                        <button class="btn btn-xs btn-info">
                                            <a href="{{ route('autorizaciones.show', ['id' => $quotation->id_quotation]) }}"><i class="fas fa-search"></i></a>
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>No hay cotizaciones</td>
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
<script>
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
@endsection