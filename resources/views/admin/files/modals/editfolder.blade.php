<div class="modal fade" id="edit-folder">
    <div class="modal-dialog">
        {!! Form::open(['route' => ['folder.edit'], 'files'=>'false', 'method' => 'POST']) !!}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title"><i class="fas fa-folder-plus"></i> Editar la carpeta @{{ folderName }}</h2>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 no-padding">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Escriba el nuevo nombre de la carpeta</div>
                                <div class="quot-data-box-content">
                                    {{ Form::text('folder_name', null, ['class' => 'form-control',
                                    'id'=>'folder','required', 'v-model' => 'folderName']) }}
                                    {!! Form::hidden('id_folder', null, ['v-model' => 'idFolder']) !!}
                                    {!! Form::hidden('folder_name_old', null, ['v-model' => 'folderNameOld']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <div class="quot-send-btn"> <input type="submit" value="Modificar" class="btn btn-novo-big">
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
