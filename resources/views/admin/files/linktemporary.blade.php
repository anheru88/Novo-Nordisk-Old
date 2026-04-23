@extends('auth.layout')
@section('content')
<div class="container">
    <div class="sharedfiles" id="app">
        <section class="content-header">
            <h1 class="shared-title">
                <i class="fas fa-book"></i> Documentos compartidos
            </h1> <br>
            Por favor descargue los documentos haciendo clic en el boton descargar.
        </section>
        <div class="box-body">
            <table id="datatable_full" data-toggle="table" data-pagination="true" data-search="true"
                class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th data-sortable="true">Documento</th>
                        <th data-sortable="true">Expira el</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($files as $key => $file)
                    <tr>
                        <td><a href="{{ url('/').'/uploads/'.$file->folder->folder_url.'/'.$file->doc_name }}"
                                target="_blank"><i class="fas fa-file"></i> {{ $file->doc_name }}</a></td>
                        <td><i class="fas fa-calendar-alt"></i> {{ $expires }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-xs">
                                <a href="{{ url('/').'/uploads/'.$file->folder->folder_url.'/'.$file->doc_name }}" target="_blank"> Descargar </a>
                                <i class="fas fa-file-download"></i></a>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- PAGINACION -->
        </div>
    </div>
</div>
@endsection
