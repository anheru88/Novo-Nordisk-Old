@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header">
        <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / <a href="{{  route('documentos.index') }}">Repositorio de
                documentos</a> / Documentos de clientes
        </div>
        <h1>
            Documentos de Clientes
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-info">
            <!-- /.box-header -->
            <div class="box-body">
                <table id="datatable_full" data-toggle="table" data-pagination="true" data-search="true"
                    class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th data-sortable="true">Cliente</th>
                            <th data-sortable="true">Creado el</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($clients->count())
                        @foreach($clients as $client)
                        <tr>
                            <td><a
                                    href="{{ route('files.edit',['file' => $client->id_client]) }}">{{ $client->client_name }}</a>
                            </td>
                            <td>{{ date('d-m-Y',strtotime($client->created_at)) }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td>No hay registros</td>
                        </tr>
                        @endif

                    </tbody>
                </table>
                <!-- PAGINACION -->
            </div>
            <!-- /.box-body -->
        </div>
    </section>
</div>
<!-- /.content -->
@endsection