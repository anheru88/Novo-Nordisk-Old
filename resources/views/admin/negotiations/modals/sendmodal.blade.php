<div class="modal fade " id="modal-voucher">
    <div class="modal-dialog modal-md">
        {!! Form::open(['route' => 'negociaciones.sendEmail','method'=> 'POST', 'files' => 'false']) !!}
        <div class="modal-content" class="animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="fa fa-barcode"></i> Negociación @{{ negoNumber }}</h3>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="title-datos">Datos de la negociación</div>
                    <div class="datos">
                        <div class="datos-text">
                            <strong>Negociación #: </strong> @{{ negoNumber }}
                        </div>
                        <div class="datos-text">
                            <strong>Cliente: </strong> @{{ client }}
                        </div>
                        <div class="datos-text">
                            <strong>Canal de venta: </strong> @{{ channel }}
                        </div>
                    </div>
                    <div class="title-datos">Validez</div>
                    <div class="datos">
                        <div class="datos-text">
                            <strong>Vigente desde: </strong> @{{ start }}
                        </div>
                        <div class="datos-text">
                            <strong>Vigente hasta: </strong> @{{ end }}
                        </div>

                    </div>
                </div>
                <div class="content-divider"></div>
                <div class="col-xs-12">
                    <div class="datos">
                        <div class="datos-text">
                            Email para enviar la negociación:
                        </div>
                        <div class="modal-input">
                            <input name="idNego" type="hidden" :value="idNegotiation">
                            <input name="email" type="text" :value="email" class="form-control" id="email" required>
                        </div>
                        <div class="btns-modal">
                            <div class="datos-btn">
                                {{ Form::button('<i class="fa  fa-paper-plane"></i> Enviar por email', ['type' =>
                                'submit', 'class'=> 'btn btn-success pull-right' ]) }}
                            </div>
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
