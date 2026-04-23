@extends('admin.layout')
@section('content')
<div class="content-wrapper" id="app">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header">
        <div class="bread-crumb">
            <a href="#">Home</a> / Gestión de Precios
        </div>
        <h1>
            Gestión de Precios
        </h1>
        <div class="tools-header">
            <div class="tools-menu-btn-icon">Listado de precios vigentes</div>
            @can('prices.masive')
            <div class="tools-menu-btn ligthprimary-bg white-text">
                <div class="tools-menu-btn-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="tools-menu-btn-text" data-toggle="modal" data-target="#modal-upload"> Carga masiva</div>
            </div>
            @endcan
        </div>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Error!</strong> Revise los campos obligatorios.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if(Session::has('info'))
        <div class="alert alert-info">
            {{Session::get('info')}}
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-warning">
            {{Session::get('error')}}
        </div>
        @endif
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="datatable1" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th >#</th>
                                    <th >Nombre de lista</th>
                                    <th >Subida el </th>
                                    <th >Estado</th>
                                    <th  class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($pricelists->count())
                                @foreach($pricelists as $key => $pricelist)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><a
                                            href="{{ route('prices.show',['price' => $pricelist->id_pricelists ]) }}">{{ strtoupper($pricelist->list_name) }}</a>
                                    </td>
                                    <td><i class="fas fa-calendar-alt"></i>
                                        {{ date('d-m-Y',strtotime($pricelist->created_at)) }}</td>
                                    <td>
                                        {!! statusList($pricelist->active) !!}
                                    </td>
                                    <td>
                                            <a class="btn btn-xs btn-info"
                                                href="{{ route('prices.show', ['price' => $pricelist->id_pricelists]) }}"><i
                                                    class="fas fa-search"></i></a>
                                        <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modal-editdate" v-on:click="getPriceData({{ $pricelist->id_pricelists }})">
                                            <i class="fa fa-calendar-check-o"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>No hay listas</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <!-- PAGINACION -->
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
    @include('admin.prices.editdatemodal')

    <div class="modal fade" id="modal-upload">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['route' => 'prices.masive', 'method' => 'POST','files'=>'true']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"><i class="ion ion-ios-search-strong"></i> Carga masiva de precios</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">Escriba el nombre de la lista</div>
                    <div class="form-group">
                        {{ Form::text('list_name', null, ['class' => 'form-control', 'id'=>'list_name','required']) }}
                    </div>
                    <div class="form-group">Seleccione un autorizador
                        {!! Form::select('id_authorizer',$authorizers, null,['class'
                        => 'form-control focus filter-table-textarea', 'placeholder' => 'Seleccione',
                        'required']) !!}
                    </div>
                    <div class="form-group">Seleccione el archivo que desea cargar
                        <a href="{{ asset( 'downloads') }}/carga_masiva_precios.xlsx">Descargar plantilla</a>

                    </div>
                    <div class="quot-data-box-content">
                        <div class="file-loading">
                            {!! Form::file ('doc', ['class'=>'file', 'type'=>'file','id'=>'file-1',
                            'data-show-preview'=>'false']) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default btn-novo pull-right">Cargar</button>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    @include('admin.products.partials.form')
</div>
@endsection

@section('pagescript')
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script src="{{ asset('js/api.js') }}"></script>
{{-- <script src="{{ asset('js/prices_utils.js') }}"></script> --}}
<script>
    $("#file-1").fileinput({
        allowedFileExtensions: ['xls','xlsx'],
        browseClass: "btn btn-primary btn-block",
        maxFileSize: 4000,
        maxFileCount: 1,
        showUpload: false,
        showRemove: true,
        showCaption: true,
        browseOnZoneClick: false,
        showBrowse: true,
        showDrag:false,
        uploadUrl: '#',
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });
</script>
@endsection
