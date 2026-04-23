@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header row">
        <div class="col-md-12 float-left">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / Productos
            </div>
            <h1>
                Productos
            </h1>
        </div>
        <div class="col-md-12 float-right">
            <div class="tools-header">
                @can('products.create')
                <div class="tools-menu-btn granite-text">
                    <div class="tools-menu-btn-icon"><i class="fas fa-plus-circle"></i></div>
                    <div class="tools-menu-btn-text"><a href="{{ route('products.create') }}"> Crear nuevo producto</a>
                    </div>
                </div>
                @endcan
                @can('products.masive')
                <div class="tools-menu-btn pull-right ligthprimary-bg white-text">
                    <div class="tools-menu-btn-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="tools-menu-btn-text" data-toggle="modal" data-target="#modal-upload"> Carga masiva</div>
                </div>
                @endcan
                <div class="pull-right white-text" style="margin-top: 10px;">
                    {!! Form::open(['route' => ['products.export'], 'method' => 'POST', 'files' => 'false']) !!}
                    {{ Form::button('<i class="fas fa-download"></i> Reporte de productos',['type' => 'submit', 'class' => 'btn btn-success btn-sm']) }}
                    {!! Form::close() !!}
                </div>
                {{-- <div class="pull-right white-text" style="margin-top: 10px;">
                    {!! Form::open(['route' => ['products.passed'], 'method' => 'POST', 'files' => 'false']) !!}
                    {{ Form::button('<i class="fas fa-user"></i> Codigos SAP nueva tabla',['type' => 'submit', 'class' => 'btn btn-info btn-sm']) }}
                    {!! Form::close() !!}
                </div> --}}
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-info">
            <!-- /.box-header -->
            <div class="box-body">
                <table id="datatable1" data-toggle="table" data-pagination="true" data-search="true" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th >Producto</th>
                            <th >Nombre Generico</th>
                            <th >Linea</th>
                            <th  width="30%">Presentación</th>
                            <th >Vigente</th>
                            <th >Vigencia (hasta)</th>
                            @can('products.edit')
                            <th></th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @if($productos->count())
                        @foreach($productos as $producto)
                        @php
                        $product_date = date('Y-m-d',strtotime($producto->prod_valid_date_end));
                        $today = date('Y-m-d');
                        if($product_date >= $today){
                        $vigencia = "SI";
                        $vencida = "";
                        }else{
                        $vigencia = "NO";
                        $vencida = "red-table";
                        }
                        @endphp
                        <tr class="{{ $vencida }}">
                            <td><a
                                    href="{{ route('products.show',['product' => $producto->id_product]) }}">{{ $producto->prod_name }}</a>
                            </td>
                            <td>{{ $producto->prod_name }}</td>
                            <td>{{ $producto->productLine->line_name }}</td>
                            <td>{{ $producto->prod_package }}</td>
                            <td>{{ $vigencia }}</td>
                            <td>{{ date('d-m-Y',strtotime($producto->prod_valid_date_end)) }}</td>
                            @can('products.edit')
                            <td>
                                @can('products.edit')
                                <a class="btn btn-xs btn-warning"  href="{{ route('products.edit', ['product' => $producto->id_product]) }}">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @endcan
                                @can('products.delete')
                                <a class="btn btn-xs btn-danger" href="{{ route('products.destroy', ['id' => $producto->id_product]) }}"
                                    onclick="return confirm('¿Seguro que desea eliminar el producto {{  $producto->prod_name}} ?')">
                                    <i class="fas fa-trash-alt"></i></a>
                                @endcan
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
                <!-- PAGINACION -->
            </div>
            <!-- /.box-body -->
        </div>

    </section>

    <div class="modal fade" id="modal-upload">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['route' => 'products.masive', 'method' => 'POST','files'=>'true']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"><i class="ion ion-ios-search-strong"></i> Carga masiva de clientes</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">Seleccione el archivo que desea cargar
                        <a href="{{ URL::to('/') }}/templates/carga_masiva_productos_template.xlsx">Descargar
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
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
 <script>
    $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
</script>
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
