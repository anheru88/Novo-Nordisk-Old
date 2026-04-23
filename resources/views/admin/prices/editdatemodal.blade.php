<div class="modal fade " id="modal-editdate">
    <div class="modal-dialog modal-md">
        {!! Form::open(['route' => 'prices.updatedate', 'method'=>'POST', 'files' => 'false']) !!}
        <div class="modal-content" class="animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="fa fa-barcode"></i> Editar fechas de: @{{ listName }}</h3>
                {{-- @{{ idQuotation }} --}}
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    {{-- <div class="title-datos">Validez</div> --}}
                    <div class="datos">
                        <div class="datos-text">
                            <strong>Vigente desde: </strong> 
                            {{-- @{{ startNeg }} --}}
                            <div class="modal-input">
                                <input name="idPrices" type="hidden" :value="idPrices">
                                {{ Form::date('prod_valid_date_ini', null, array('required','id'=>'modal_date_ini','class' => 'form-control', 'v-model' => 'startPrice')) }}
                            </div>
                        </div>
                        <div class="datos-text">
                            <strong>Vigente hasta: </strong> 
                            {{-- @{{ endNeg }} --}}
                            <div class="modal-input">
                                {{ Form::date('prod_valid_date_end', null, array('required','id'=>'modal_date_end','class' => 'form-control', 'v-model' => 'endPrice')) }}
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