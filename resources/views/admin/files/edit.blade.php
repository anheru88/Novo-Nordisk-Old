@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header">
        <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / <a href="{{  route('files.index') }}">Documentos</a> /
            {{ $clients->client_name }}
        </div>
        <h1>
            Documentos de {{ $clients->client_name }}
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-info">
            <!-- Button trigger modal -->
            <div class="box-body">
                <table id="datatable_full" data-toggle="table" data-pagination="true" data-search="true"
                    class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th data-sortable="true">Documento</th>
                            <th data-sortable="true">Subido el</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($folders->count())
                        @foreach($folders as $key => $folder)
                        <tr>
                            <td><a href="{{ url('/').'/uploads/'.$clients->id_client.'/'.$folder->file_name }}"
                                    target="_blank">{{ $folder->file_name }}</a></td>
                            <td>{{ date('d-m-Y',strtotime($folder->created_at)) }}</td>
                            <td>
                                <button class="btn btn-xs btn-info"><a
                                        href="{{ url('/').'/uploads/'.$clients->id_client.'/'.$folder->file_name }}"
                                        download><i class="fas fa-file-download"></i></a></button>
                                <button class="btn btn-xs btn-danger"><a
                                        href="{{ route('files.destroy', ['id'=>$folder->id_files]) }}"><i
                                            class="fas fa-trash-alt"></i></a></button>
                            </td>
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

    <section class="content">
        <div class="box box-info">
            <!-- /.box-header -->
            <div class="box-header">
                <h2><i class="ion ion-compose"></i> Agregar documentos</h2>
            </div>
            <div class="box-body">
                {!! Form::open(['route' => 'filesUpload', 'name' => 'cliente', 'id' => 'cliente', 'files'=>'true',
                'method' => 'POST']) !!}
                <div class="container-fixed">
                    <div class="row quot-first-data">
                        <div class="col-xs-12">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Seleccione los documentos que desea adjuntar al
                                    repositorio del cliente</div>
                                {{ Form::hidden('id_client', $clients->id_client) }}
                                <div class="quot-data-box-content">
                                    <div class="file-loading">
                                        {!! Form::file ('docs[]', ['class'=>'file', 'type'=>'file',
                                        'data-min-file-count'=>'0', 'id'=>'file'
                                        ,'multiple','data-browse-on-zone-click'=>'true']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <div class="quot-send-btn">
                        <input type="submit" value="Subir archivos" class="btn btn-novo-big">
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.box-body -->
        </div>
    </section>
</div>
<!-- Modal Para compartir documentos-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-info" style="text-align: center"> {{ $clients->client_name }} </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'filesresponse', 'method' => 'POST']) !!}
                <h2 style="text-align: center"><u> Documentos </u></h2>
                @foreach($folders as $key => $folder)
                <div class="form-check">
                    <input class="form-check-input" name="id_files[]" type="checkbox" value="{{ $folder->id_files }}"
                        id="flexCheckDefault{{ $folder->id_files }}">
                    <label class="form-check-label" for="flexCheckDefault{{ $folder->id_files }}">{{
                        $folder->file_name }}</label>
                </div>
                @endforeach
                <div class="box-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="destinatario" class="col-form-label" name="destinatario">Destinatario:</label>
                            <input type="text" class="form-control text-primary" id="destinatario" name="destinatario"
                                placeholder="cliente@gmail.com">
                        </div>
                        <div class="form-group">
                            <label for="mensaje" class="col-form-label" name="mensaje">Mensaje:</label>
                            <textarea class="form-control" id="message-text"
                                placeholder="Adjunto los siguientes documentos" name="mensaje" id="mensaje"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-8 col-md-6 no-padding text-center">
                    <div class="quot-data-box">
                        <div class="quot-data-box-content">
                            <label for="timelast" class="col-form-label">Tiempo de expiración del
                                link</label>
                            <select class="form-select btn btn-primary btn-sm" id="timelast" name="timelast"
                                aria-label="Default select example">
                                <option selected>Elija un tiempo</option>
                                <option value="10min">10 min</option>
                                <option value="30min">30 min</option>
                                <option value="60min">1 hora</option>
                                <option value="3hr">3 horas</option>
                                <option value="8hr">8 horas</option>
                                <option value="14hr">14 horas</option>
                                <option value="1d">1 día</option>
                                <option value="2d">2 día</option>
                                <option value="5d">5 días</option>
                                <option value="1sem">1 semana</option>
                                <option value="2sem">2 semanas</option>
                                <option value="1mes">1 mes</option>
                                <option value="2meses">2 meses</option>
                                <option value="infinito">Sin límite de caducidad</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="hidden" name="id" value="{{$id}}">
                    <button type="submit" class="btn  btn-primary" style="margin: 0px auto;">
                        Compartir</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.box-header -->
<!-- /.content -->
@endsection

@section('pagescript')
<script>
    jQuery("#file").fileinput({
        allowedFileExtensions: ['jpg', 'png', 'gif','doc','pdf','xls','docx','xlsm'],
        browseClass: "btn btn-primary btn-block",
        removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
        maxFileSize: 4000,
        maxFileCount: 5,
        showUpload: false,
        showRemove: true,
        showCaption: false,
        browseOnZoneClick: true,
        showBrowse: false,
        showDrag:true,
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });
</script>
@endsection
