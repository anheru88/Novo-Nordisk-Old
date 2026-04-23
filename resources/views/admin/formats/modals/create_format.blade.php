<div class="modal fade " id="modal-create">
    <div class="modal-dialog modal-md">
        {!! Form::open(['route' => 'formats.store', 'method'=>'POST', 'files' => 'false']) !!}
        <div class="modal-content" class="animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="fa fa-barcode"></i> Crear formato nuevo</h3>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    @if ($ftype->id_formattype     == '1')
                    <div class="title-datos">Titulo</div>
                    {!! Form::hidden('formattype', $ftype->id_formattype,) !!}
                    {{ Form::text('title', null, ['class' => 'form-control', 'id'=>'title']) }}
                    @elseif ($ftype->id_formattype == '5')
                    <div class="title-datos">Referencia de la certificación</div>
                    {!! Form::hidden('formattype', $ftype->id_formattype,) !!}
                    {{ Form::text('reference', null, ['class' => 'form-control', 'id'=>'reference']) }}
                    @endif
                </div>
                <div class="content-divider"></div>
                <div class="col-xs-12">
                    <div class="btns-modal">
                        <div class="datos-btn">
                            {{ Form::button('<i class="fa  fa-plus"></i> Crear', ['type' => 'submit', 'class'=> 'btn btn-default pull-right' ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
        {!! Form::close() !!}
    </div>
    <!-- /.modal-dialog -->
</div>