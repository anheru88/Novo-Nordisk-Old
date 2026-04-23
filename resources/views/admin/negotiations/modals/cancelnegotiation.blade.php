<div class="modal fade" id="modal-anular">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['route' => ['negociaciones.anular',$negotiation->id_negotiation], 'method' => 'PUT',
            'files'
            =>
            'false']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">¿Realmente desea anular la negociación
                    #{{ $negotiation->negotiation_consecutive }}?</h2>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 no-padding">
                    <div class="quot-data-box">
                        <label>Ingrese los comentarios de por qué anula la negociación*</label>
                        <div class="form-group">
                            {!! Form::textarea('comments', null, ['class' => 'form-control',
                            'id'=>'comments','required']) !!}
                            {!! Form::hidden('id_negotiation', $negotiation->id_negotiation) !!}
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> CANCELAR </a>
                {{ Form::button('<i class="fas fa-check"></i> Anular ', ['type' => 'submit', 'class' => 'btn
                btn-sm btn-success'] ) }}
            </div>

            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
