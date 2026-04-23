@extends('admin.layout')
@section('content')
<div class="content-wrapper" id="app">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / Cotizaciones
            </div>
            <h1>
                Cotizaciones {{ getCotTitle($estado) }}
            </h1>
            <div class="tools-header">
                @can('cotizaciones.create')
                <div class="tools-menu-btn granite-text">
                    <div class="tools-menu-btn-icon"><a href="{{ route('cotizaciones.create') }}"><i
                                class="fas fa-plus-circle"></i></a></div>
                    <div class="tools-menu-btn-text"><a href="{{ route('cotizaciones.create') }}">Crear nueva</a></div>
                </div>
                @endcan
            </div>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <form class="{{ route('cotizaciones.index') }}" method="GET">
                            <div class="form-group mb-2">
                                <div class="container-fluid no-padding">
                                        <div class="col-md-10 no-padding">
                                            <div class="search-table">
                                                {!! Form::select('quantity', [
                                                    '10' => '10',
                                                    '20' => '20',
                                                    '50' => '50',
                                                    '100' => '100',
                                                    ], $quantity, ['class'=>"form-control select-min", 'onchange'=>'this.form.submit()'])
                                                !!}
                                                <input type="text" class="form-control" id="filter" name="filter"
                                                    placeholder="Buscar" value="{{ $filter }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-default mb-2">Buscar</button>
                                        </div>
                                    </div>
                            </div>
                        </form>
                        <table id="datatable1" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>@sortablelink('quota_consecutive', 'Cotización #')</th>
                                    <th>@sortablelink('id_client', 'Cliente')</th>
                                    <th>@sortablelink('id_channel', 'Canal')</th>
                                    <th>@sortablelink('created_by', 'CAM')</th>
                                    <th>@sortablelink('quota_date_ini', 'Vigencia desde')</th>
                                    <th>@sortablelink('quota_date_end', 'Vigencia hasta')</th>
                                    <th>@sortablelink('status_id', 'Estado')</th>
                                    <th class="no-sort" width="80px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(sizeof($quotations) > 0)
                                @foreach($quotations as $quotation)
                                <tr>
                                    <td><a href="{{ route('cotizaciones.show',['cotizacione' => $quotation->id_quotation ]) }}">{{ $quotation->quota_consecutive }}</a></td>
                                    <td>{{ $quotation->cliente->client_name }}</td>
                                    <td>{{ $quotation->channel->channel_name }}</td>
                                    <td>{{ $quotation->creator->name }}</td>
                                    <td><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y',strtotime($quotation->quota_date_ini)) }}</td>
                                    <td><i class="fas fa-calendar-alt"></i> {{ date('d-m-Y',strtotime($quotation->quota_date_end)) }}</td>
                                    <td>
                                        @if ($quotation->status_id > 0)
                                        <div class="label" style="background-color:{{ $quotation->status->status_color }}; font-size: 11px; border-radius: 3px;}">
                                            {!! $quotation->status->status_symbol !!}  {{ $quotation->status->status_name }}
                                        </div>
                                        @endif
                                    </td>
                                    <td style="width: 130px">
                                        <a href="{{ route('cotizaciones.show',['cotizacione' => $quotation->id_quotation ]) }}" class="btn btn-xs btn-info"><i class="fas fa-search"></i></a>
                                        <button class="btn btn-xs btn-warning" data-toggle="modal"
                                            data-target="#modal-editdate"
                                            v-on:click="sendQuotaEmail({{ $quotation->id_quotation }})">
                                            <i class="fa fa-calendar-check-o"></i>
                                        </button>
                                        @if($quotation->status_id < 6)
                                            @can('cotizaciones.edit')
                                            <button class="btn btn-xs btn-warning">
                                                <a href="{{ route('cotizaciones.edit', ['cotizacione' => $quotation->id_quotation]) }}">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            </button>
                                            @endcan
                                            @can('cotizaciones.destroy')
                                            <a href="{{ route('cotizaciones.destroy', ['id' => $quotation->id_quotation]) }}"
                                                class="btn btn-xs btn-danger"
                                                onclick="return confirm('¿Seguro que desea eliminar la cotización #{{  $quotation->id_quotation}} ?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            @endcan
                                        @else
                                            @if($quotation->status_id == 6)
                                                <button class="btn btn-xs btn-success" data-toggle="modal"
                                                    data-target="#modal-voucher"
                                                    v-on:click="sendQuotaEmail({{ $quotation->id_quotation }})">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                                <a href="cotizaciones/{{ $quotation->id_quotation }}/pdf" target="_blank" class="btn btn-xs btn-danger">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7" style="text-align: center;">No hay cotizaciones</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <p>
                            Visualización {{$quotations->count()}} de {{ $quotations->total() }} cotización(es).
                        </p>
                        {{$quotations->appends(request()->all())->links()}}
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
        @include('admin.quotations.modals.editdatemodal')
        @include('admin.quotations.modals.sendmodal')
    </div>
</div>
<!-- /.content -->
@endsection
@section('pagescript')
<script src="{{ asset('js/quotation_utils.js') }}"></script>
@endsection
