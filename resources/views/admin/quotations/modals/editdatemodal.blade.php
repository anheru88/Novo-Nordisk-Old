<div class="modal fade " id="modal-editdate">
    <div class="modal-dialog modal-md">
        {!! Form::open(['route' => 'cotizaciones.updatedate', 'method'=>'POST', 'files' => 'false']) !!}
        <div class="modal-content" class="animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="fa fa-barcode"></i> Cotizacion: @{{ quotaNumber }}</h3>
                {{-- @{{ idQuotation }} --}}
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="title-datos">Datos de la cotizacion</div>
                    <div class="datos">
                        <div class="datos-text">
                            <strong>Cotización #: </strong> @{{ quotaNumber }}
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
                            <strong>Vigente desde: </strong>
                            {{-- @{{ start }} --}}
                            <div class="modal-input">
                                <input name="idQuota" type="hidden" :value="idQuotation">
                                {{ Form::date('quota_date_ini', null, array('required','id'=>'quota_date_ini','class' => 'form-control', 'v-model' => 'start')) }}
                            </div>
                        </div>
                        <div class="datos-text">
                            <strong>Vigente hasta: </strong>
                            {{-- @{{ end }} --}}
                            <div class="modal-input">
                                {{ Form::date('quota_date_end', null, array('required','id'=>'product_date_end1','class' => 'form-control', 'v-model' => 'end')) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-divider"></div>
                <div class="col-xs-12">
                    <div class="btns-modal">
                        <div class="datos-btn">
                            {{ Form::button('<i class="fa  fa-calendar"></i> Modificar', ['type' => 'submit', 'class'=> 'btn btn-default pull-right' ]) }}
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
