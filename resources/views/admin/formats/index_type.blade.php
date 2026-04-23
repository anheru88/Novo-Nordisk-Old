@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / Documentos del tipo {{ $ftype->format_name }}
            </div>
            <h1>
                <i class="fas fa-book"></i> Documentos tipo {{ $ftype->format_name }}
            </h1>
        </section>
        <section class="content">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-xs btn-success" style="margin: 10px;" data-toggle="modal"
                                data-target="#modal-create">
                                <i class="fa fa-plus"></i>
                            </button>
                            @if (count($docs) > 0 || count($docsCer) > 0)
                            <table data-toggle="table" data-pagination="true" data-search="true"
                                class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th data-sortable="true">Nombre del documento</th>
                                        <th data-sortable="true" class="text-center">Creado el </th>
                                        <th data-sortable="true" class="text-center">Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($docs as $doc)
                                    <tr id="ads" class="animated">
                                        <td>
                                        <a href="{{ route('formats.editcot', ['format' => $doc->id_format, 'type' => $doc->id_formattype]) }}">
                                        <i class="fas fa-file-alt">

                                        </i> {{ $doc->name }}  </a>

                                        </td>
                                        <td class="text-center">
                                            <i class="fa fa-calendar"></i>
                                            {{  date('d-m-Y', strtotime($doc->created_at)) }}
                                        </td>
                                        <td class="text-center">
                                            {!! statusList($doc->active) !!}
                                        </td>
                                        <td class="text-center">
                                        </td>
                                    </tr>
                                    @endforeach
                                    @foreach ($docsCer as $doc)
                                    <tr id="ads" class="animated">
                                        <td>
                                            <a
                                                href="{{ route('formats.editcer',['format' => $doc->id, 'type' => $doc->id_formattype ]) }}"><i
                                                    class="fas fa-file-alt"></i> {{ $doc->reference }} </a>
                                        </td>
                                        <td class="text-center">
                                            <i class="fa fa-calendar"></i>
                                            {{  date('d-m-Y', strtotime($doc->created_at)) }}
                                        </td>
                                        <td class="text-center">
                                            {!! statusList($doc->active) !!}
                                        </td>
                                        <td class="text-center">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            No hay documentos de este tipo
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</div>
@include('admin.formats.modals.create_format')
@endsection
