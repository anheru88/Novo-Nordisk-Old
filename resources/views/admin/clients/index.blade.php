@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header">
        <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / Clientes
        </div>
        <h1>
            Clientes
        </h1>
        <div class="tools-header">
            @can('clients.create')
            <div class="tools-menu-btn granite-text">
                <div class="tools-menu-btn-icon"><i class="fas fa-plus-circle"></i></div>
                <div class="tools-menu-btn-text"><a href="{{ route('clients.create') }}">Crear cliente</a></div>
            </div>
            @endcan
            <!--
            <div class="tools-menu-btn">
                <div class="tools-menu-btn-icon"><i class="fas fa-file-excel"></i></div>
                <div class="tools-menu-btn-text"><a href="{{ route('cotizaciones.create') }}">Listado en Excel</a></div>
            </div>
            <div class="tools-menu-btn">
                <div class="tools-menu-btn-icon"><i class="ion ion-printer"></i></div>
                <div class="tools-menu-btn-text"> Imprimir</div>
            </div>
        -->
            @can('clients.masive')
            <div class="tools-menu-btn pull-right ligthprimary-bg white-text">
                <div class="tools-menu-btn-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="tools-menu-btn-text" data-toggle="modal" data-target="#modal-upload"> Carga de clientes
                </div>
            </div>
            @endcan
            <div class="pull-right white-text" style="margin-top: 10px;">
                {!! Form::open(['route' => ['clients.export'], 'method' => 'POST', 'files' => 'false']) !!}
                    {{ Form::button('<i class="fas fa-download"></i> Reporte de clientes',['type' => 'submit', 'class' => 'btn btn-success btn-sm']) }}
                {!! Form::close() !!}
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-info">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table id="informe2" data-toggle="table" data-pagination="true" data-search="true" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th >Cliente</th>
                                <th >Canal</th>
                                <th >Codigo SAP</th>
                                <th >Tipo de cliente</th>
                                <th >Forma de pago</th>
                                <th >Cupo</th>
                                <th >Estado</th>
                                @can('clients.edit')
                                <th  class="no-sort"></th>
                                @endcan
                                @can('clients.delete')
                                <th  class="no-sort"></th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @if($clientes->count())
                            @foreach($clientes as $cliente)
                            <tr>
                                <td><a
                                        href="{{ route('clients.show', ['client' => $cliente->id_client]) }}">{{ $cliente->client_name }}</a>
                                </td>
                                <td>{{ $cliente->channel->channel_name }}</td>
                                <td width="102px">
                                    {{ $cliente->client_sap_code }}
                                </td>
                                <td>{{ $cliente->type->type_name }}</td>
                                <td>
                                    @if (!empty($cliente->payterm))
                                    {{ $cliente->payterm->payterm_name}}
                                    @else
                                    -.-
                                    @endif
                                </td>
                                <td>${{ number_format( $cliente->client_credit,0, ",", ".") }}</td>
                                <td>
                                    @if ($cliente->active == 0)
                                    <span class="label label-danger">Inactivo</span>
                                    @else
                                    <span class="label label-success">Activo</span>
                                    @endif
                                </td>
                                @can('clients.edit')
                                <td width="60px">
                                    @if ($cliente->active == 0)
                                    <div class="minibtn"><a
                                            href="{{ route('clients.edit', ['client' => $cliente->id_client]) }}"
                                            class="btn btn-xs btn-warning"><i class="fas fa-pen"></i></a></div>
                                    @elseif($rol != "cam")
                                    <div class="minibtn"><a
                                            href="{{ route('clients.edit', ['client' => $cliente->id_client]) }}"
                                            class="btn btn-xs btn-warning"><i class="fas fa-pen"></i></a></div>
                                    @endif
                                </td>
                                @endcan
                                @can('clients.delete')
                                <td>
                                    <a href="{{ route('clients.destroy', ['id' => $cliente->id_client]) }}"
                                        class="btn btn-xs btn-danger"
                                        onclick="return confirm('¿Seguro que desea eliminar el cliente {{ $cliente->client_name }} ?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                                @endcan
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
            </div>
            <!-- /.box-body -->
        </div>
    </section>


    <div class="modal fade" id="modal-upload">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['route' => 'clients.masive', 'method' => 'POST','files'=>'true']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"><i class="ion ion-ios-search-strong"></i> Carga masiva de clientes</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">Seleccione el archivo que desea cargar
                        <a href="{{ URL::to('/') }}/templates/carga_masiva_precios_templates.xlsx">Descargar
                            plantilla</a>
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
</div>
<!-- /.content -->
@endsection

@section('pagescript')
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script>
    $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

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
