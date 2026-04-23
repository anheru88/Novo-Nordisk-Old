<div class="modal fade" id="modal-folder">
    <div class="modal-dialog">
        {!! Form::open(['route' => 'documentos.createfolder', 'method' => 'POST']) !!}
        {{ csrf_field() }}
        {{ Form::hidden('parent',$parent) }}
        {{ Form::hidden('url',$url) }}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title"><i class="fas fa-folder-plus"></i> Crear nueva carpeta</h2>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 no-padding">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Nombre de la carpeta</div>
                                <div class="quot-data-box-content">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-plus-square"></i>
                                        </div>
                                        {{ Form::text('folder_name', null, ['class' => 'form-control',
                                        'id'=>'folder','required']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" value="Crear carpeta" class="btn btn-novo-big">
            </div>
        </div>
        {!! Form::close() !!}
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
