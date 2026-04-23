@extends('admin.layout')
@section('content')
    <div class="content-wrapper" id="app">
        @include('admin.layouts.breadcrumbs')
        <div class="quotation" id="app">
            <section class="content-header row">
                <div class="col-md-12 float-left">
                    <div class="bread-crumb">
                        <a href="{{ route('home') }}">Inicio</a> / ARP / {{ $arp->name }}
                    </div>
                    <h1>
                        {{ $arp->name }}
                    </h1>
                </div>
                <div class="col-md-12 float-right">
                    <div class="tools-header">
                        <div style="margin-top: 10px">
                            <button class="btn btn-md btn-info" data-toggle="modal" data-target="#modal-create">
                                <i class="fas fa-plus-circle"></i> Crear nuevo servicio
                            </button>
                            @php
                                $pbc = 0;
                                if ($arp->pbc != null) {
                                    $pbc = $arp->pbc->id;
                                }
                            @endphp
                            @if ($arp->pbc != null)
                                <button class="btn btn-md bg-orange btn-flat" style="margin-right: 5px"
                                    v-on:click="editPbc({!! $arp->id !!},'{!! $arp->name !!}',{!! $pbc !!})"
                                    data-toggle="modal" data-target="#modal-pbcedit">
                                    <i class="fas fa-pen"></i> Editar PBC
                                </button>
                            @else
                                <button class="btn btn-md bg-orange btn-flat" style="margin-right: 5px"
                                    v-on:click="addPbc({!! $arp->id !!},'{!! $arp->name !!}')"
                                    data-toggle="modal" data-target="#modal-pbc">
                                    <i class="fas fa-plus"></i> Agregar PBC
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-xs-12 ">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <i class="fa fa-cube" aria-hidden="true"></i>
                                <h3 class="box-title">Datos creados</h3>
                            </div>
                            <div class="box-body">
                                <table id="datatable1" class="table table-striped table-hover">
                                    <tbody>
                                        @if (sizeof($servicesArp) <= 0)
                                            <tr>
                                                <td> No hay datos registrados</td>
                                            </tr>
                                        @else
                                            @foreach ($servicesArp as $key => $service)
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-12 col-sm-8">
                                                                {!! $service->name !!}
                                                            </div>
                                                            <div class="col-12 col-sm-4 ">
                                                                <div class="pull-right">
                                                                    <button class="btn btn-sm btn-warning"
                                                                        style="margin-right: 5px"
                                                                        v-on:click="editArp({!! $service->id !!},'{!! $service->name !!}')"
                                                                        data-toggle="modal" data-target="#modal-edit">
                                                                        <i class="fas fa-pen"></i> Editar productos
                                                                    </button>
                                                                    <a href="{{ route('serviceArp.destroy', $service->id) }}"
                                                                        class="btn btn-sm btn-danger pull-right"
                                                                        onclick="return confirm('¿Seguro que desea eliminar la nota {{ $service->name }} ?')">
                                                                        <i class="fas fa-trash-alt"></i> ELIMINAR
                                                                    </a>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr></tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('admin.arp.modal.form-modal')
        @include('admin.arp.modal.edit-modal')
        @include('admin.arp.modal.pbc-modal')
        @include('admin.arp.modal.pbcedit-modal')
    </div>

@endsection

@section('pagescript')
    <script src="{{ asset('js/arp.js') }}"></script>
@endsection
