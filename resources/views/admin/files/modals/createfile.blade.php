<div class="modal fade" id="modal-doc">
    <div class="modal-dialog">
        {!! Form::open(['action' => 'DocsController@createFile', 'files'=>'true', 'method' => 'POST']) !!}
        {{ csrf_field() }}
        {{ Form::hidden('parent',$parent) }}
        {{ Form::hidden('url',$url) }}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title"><i class="fas fa-folder-plus"></i> Subir nuevo documento</h2>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 no-padding">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="container-fixed">
                                <div class="row quot-first-data">
                                    <div class="col-xs-12">
                                        <div class="quot-data-box">
                                            <div class="quot-data-box-title">Seleccione los documentos que desea
                                                adjuntar al
                                                repositorio del cliente</div>
                                            <div class="quot-data-box-content">
                                                <div class="file-loading">
                                                    {!! Form::file ('docs[]', ['class'=>'file',
                                                    'type'=>'file','data-min-file-count'=>'0', 'id'=>'file'
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
