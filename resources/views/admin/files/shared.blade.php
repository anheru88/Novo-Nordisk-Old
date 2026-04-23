@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header">
        <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / Documentos compartidos
        </div>
        <h1>
            Confirmación de documentos compartidos
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-info">
            <div class="box-body">
                {!! Form::open(['action' => 'FilesController@sharedDocsSendEmail', 'method' => 'POST']) !!}
                <div class="col-xs-12 col-md-6">
                    <div class="docdata">La información de archivos compartidos es la siguiente:</div>
                    <div class="shared-docs">
                        <div class="msg-title">
                            Destinatario:
                        </div>
                        <div class="msg-email">
                            {{$destinatario}}
                        </div>
                        <div class="msg-title">
                            Contenido del mensaje
                        </div>
                        <div class="msg-content">
                            {{$mensaje}}
                        </div>
                        <div class="msg-title">Archivos compartidos</div>
                        <div class="gray-box-modal">
                            @foreach($files as $key => $file)
                            <div class="mb-1"><a
                                    href="{{ url('/').'/uploads'.$file->folder->folder_url.'/'.$file->doc_name }}"
                                    target="_blank">{{ $file->doc_name }}</a></div>
                            @endforeach
                        </div>
                        <div class="expira">
                            Fecha de expiracion del vinculo {{$config_time}}
                        </div>
                        <input type="hidden" name="destinatario" value="{{$destinatario}}">
                        <input type="hidden" name="mensaje" value="{{$mensaje}}">
                        <input type="hidden" name="url" value="{{$url}}">
                        <input type="hidden" name="config_time" value="{{$config_time}}">
                        <div class="msg-btns">
                            <div id="divlink" style="display: none;">{{ $url }}</div>
                            <button type="button" class="btn  btn-primary" onclick="copyToClipboard('#divlink')">Copiar
                                vínculo</button>
                            <button type="submit" class="btn  btn-primary" style="margin: 0px auto;">Confirmar
                                envio</button>
                        </div>
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
</div>
@endsection

<script>
    function copyToClipboard(elemento) {
        var $temp = $("<input>")
        $("body").append($temp);
        $temp.val($(elemento).text()).select();
        document.execCommand("copy");
        $temp.remove();
        toastr.success("Vicnulo copiado").css("width", "auto")
    }
</script>
