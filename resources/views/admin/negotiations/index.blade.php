@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="negotiation" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / Negociaciones
            </div>
            <h1>
                Negociaciones
            </h1>
            <div class="tools-header">
                <div class="tools-menu-btn granite-text">
                    <div class="tools-menu-btn-icon"><i class="fas fa-plus-circle"></i></div>
                    <div class="tools-menu-btn-text"><a href="{{ route('negociaciones.create') }}">Crear nueva</a></div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="box box-info">
                <div class="box-body">
                    <div class="table-responsive">
                        <form class="{{ route('negociaciones.index') }}" method="GET">
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
                                    <th>@sortablelink('negotiation_consecutive', 'Negociación #')</th>
                                    <th>@sortablelink('cliente.client_name', 'Cliente')</th>
                                    <th class="hidden-sm">@sortablelink('negotiation_date_ini', 'Vigencia desde')</th>
                                    <th>@sortablelink('negotiation_date_end', 'Vigencia hasta')</th>
                                    <th>@sortablelink('status_id', 'Estado')</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($negotiations->count())
                                @foreach($negotiations as $negotiation)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route('negociaciones.show',['negociacione' => $negotiation->id_negotiation ]) }}">{{
                                            $negotiation->negotiation_consecutive }}</a>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('negociaciones.show',['negociacione' => $negotiation->id_negotiation ]) }}">{{
                                            $negotiation->cliente->client_name }}</a>
                                    </td>
                                    <td class="hidden-sm">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ date('d-m-Y',strtotime($negotiation->negotiation_date_ini)) }}
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ date('d-m-Y',strtotime($negotiation->negotiation_date_end)) }}
                                    </td>
                                    <td>
                                        @if ($negotiation->status_id > 0)
                                        <div class="label"
                                            style="background-color:{{ $negotiation->status->status_color }}; font-size: 11px; border-radius: 3px;}">
                                            {!! $negotiation->status->status_symbol !!} {{
                                            $negotiation->status->status_name }}
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('negociaciones.show',['negociacione' => $negotiation->id_negotiation ]) }}"
                                            class="btn btn-xs btn-info"><i class="fas fa-search"></i></a>
                                        @if ($negotiation->status_id == 6)
                                        <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modal-editdate" v-on:click="getNegoData({{ $negotiation->id_negotiation }})">
                                            <i class="fa fa-calendar-check-o"></i>
                                        </button>
                                        @endif
                                        @if ($negotiation->status_id == 2 )
                                        @can('negociaciones.edit')
                                        <button class="btn btn-xs btn-warning">
                                            <a href="{{ route('negociaciones.edit', ['negociacione' => $negotiation->id_negotiation]) }}"><i class="fas fa-pen"></i></a>
                                        </button>
                                        @endcan
                                        @can('negociaciones.destroy')
                                        <a href="{{ route('negociaciones.destroy', ['negociacione' => $negotiation->id_negotiation]) }}" class="btn btn-xs btn-danger"
                                            onclick="return confirm('¿Seguro que desea eliminar la cotización #{{  $negotiation->id_negotiation}} ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        @endcan
                                        @else
                                        @if ($negotiation->status_id == 6)
                                        <button class="btn btn-xs btn-success" data-toggle="modal" data-target="#modal-voucher" v-on:click="sendNegoEmail({{ $negotiation->id_negotiation }})">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                        <a href="negociaciones/{{ $negotiation->id_negotiation }}/pdf?state=false" class="btn btn-xs btn-danger">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @endif
                                        @endif
                                        <a href="{{ route('negociaciones.destroy', ['negociacione' => $negotiation->id_negotiation]) }}" class="btn btn-xs btn-danger"
                                            onclick="return confirm('¿Seguro que desea eliminar la negociación #{{  $negotiation->negotiation_consecutive}} ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr colspan="5">
                                    <td colspan="7" style="text-align: center;">No hay negociaciones</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <p>
                            Visualización {{$negotiations->count()}} de {{ $negotiations->total() }} negociacion(s).
                        </p>
                        {{$negotiations->appends(request()->all())->links()}}
                    </div>
                </div>
            </div>
        </section>
        @include('admin.negotiations.modals.editdatemodal')
        @include('admin.negotiations.modals.sendmodal')
    </div>
</div>
@endsection
@section('pagescript')
<script src="{{ asset('js/negotiation_utils.js') }}"></script>
@endsection

