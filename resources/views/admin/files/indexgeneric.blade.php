@extends('admin.layout')
@section('content')
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="content-wrapper" id="app">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header">
        <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / Documentos
        </div>
        <h1>
            Repositorios de Documentos
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-info">
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-auto" style="min-height: 30px">
                            @can('docs.edit')
                            <div role="group" class="btn-group">
                                <button type="button" title="Nueva carpeta" class="btn btn-secondary"
                                    data-toggle="modal" data-target="#modal-folder"><i class="far fa-folder"></i> Crear carpeta</button>
                                @if ($url != "")
                                <button type="button" title="Subir" class="btn btn-secondary" data-toggle="modal"
                                    data-target="#modal-doc"><i class="fas fa-upload"></i> Subir archivos</button>
                                @endif
                            </div>
                            @endcan
                            @if($docs->count())
                                <button type="button" class="btn btn-primary text-right"
                                style="position: absolute; right:0; margin-right:10px"
                                data-toggle="modal" data-target="#exampleModal" v-on:click="showSharedFiles()">
                                    <i class="fas fa-share-alt"></i> Enviar los documentos
                                </button>
                                @endif
                        </div>
                        <div class="col-xs-12 col-sm-3 no-padding">
                            <div class="folder-container">
                                <div class="folder-item" onclick="location.href='{{ route('documentos.genericos')  }}'">
                                    <i class="far fa-folder"></i> Repositorio
                                </div>
                                @foreach ($folders as $folder)
                                <div class="folder-item-2"
                                    onclick="location.href='{{ route('documentos.viewfolder', ['id' => $folder->id_folder])  }}'">
                                    <i class="far fa-folder"></i> {{ $folder->folder_name }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-9 no-padding">
                            <div class="folder-actual"><i class="far fa-folder"></i> <a href="/repositorio">Repositorio</a>/{!! $breadcrumbs !!}</div>
                            <div class="doc-container">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">Nombre</th>
                                            <th data-sortable="true">Creado el</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($folders_child->count())
                                        @foreach($folders_child as $key => $folder)
                                        <tr>
                                            <td>
                                                <i class="fas fa-folder"></i>
                                                <a href="{{ route('documentos.viewfolder', ['id' => $folder->id_folder])  }}">{{ $folder->folder_name }}</a>
                                            </td>
                                            <td><i class="fas fa-calendar-alt"></i>
                                                {{ date('d-m-Y',strtotime($folder->created_at)) }}</td>
                                            <td>
                                                @can('docs.edit')
                                                <button class="btn btn-xs btn-warning"
                                                    v-on:click="editFolder({{ $folder->id_folder }},'{{ $folder->folder_name }}')"
                                                    data-toggle="modal" data-target="#edit-folder"><i
                                                        class="fas fa-pen"></i></button>
                                                <a href="{{ route('folder.destroy', ['id' => $folder->id_folder]) }}"
                                                    class="btn btn-xs btn-danger"
                                                    onclick="return confirm('¿Seguro que desea eliminar la cotización #{{  $folder->folder_name}} ?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        @if($docs->count())
                                        @foreach($docs as $key => $doc)
                                        <tr>
                                            <td><i class="fas fa-file"></i> <a
                                                    href="{{ url('/').'/uploads'.$url.'/'.$doc->doc_name  }}"
                                                    target="_blank">{{ $doc->doc_name }}</a></td>
                                            <td><i class="fas fa-calendar-alt"></i>
                                                {{ date('d-m-Y',strtotime($doc->created_at)) }}</td>
                                            <td>
                                                <button class="btn btn-xs btn-info">
                                                    <a href="{{ url('/').'/uploads'.$url.'/'.$doc->doc_name  }}"
                                                        download><i class="fas fa-file-download"></i></a>
                                                </button>
                                                @can('docs.edit')
                                                <a href="{{ route('documentos.destroy', ['id'=>$doc->id_doc]) }}"
                                                    class="btn btn-xs btn-danger"
                                                    onclick="return confirm('¿Seguro que desea eliminar la cotización #{{  $doc->doc_name}} ?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                @endcan
                                                <button class="btn btn-secondary btn-xs" v-on:click="addtoShare({{ $doc->id_doc }})">
                                                    <i class="fas fa-cloud-upload-alt"></i> Compartir
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- modal-edit create new folder -->
    @include('admin.files.modals.createfolder')
    <!-- modal-edit create new file -->
    @include('admin.files.modals.createfile')
    <!-- modal-edit folder name -->
    @include('admin.files.modals.editfolder')
    <!-- modal-share docs -->
    @include('admin.files.modals.sharedocs')
</div>
<!-- /.content -->
@endsection

@section('pagescript')
<script src="{{ asset('js/repository.js') }}"></script>
<script>
    jQuery("#file").fileinput({
        allowedFileExtensions: ['jpg', 'png', 'gif','doc','pdf','xls','docx','xlsx','pptx'],
        browseClass: "btn btn-primary btn-block",
        removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
        maxFileSize: 5000,
        maxFileCount: 25,
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
    var toggler = document.getElementsByClassName("carete");
    var i;

    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("carete-down");
        });
    }
</script>
@endsection
