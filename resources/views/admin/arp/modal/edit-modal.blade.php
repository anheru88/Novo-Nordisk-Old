<div class="modal fade " id="modal-edit">
    <div class="modal-dialog modal-lg">
        {!! Form::open(['route' => ['serviceArp.update', 'id' => $arp->id], 'method'=>'POST', 'files' => 'false']) !!}
        {!! Form::hidden('arp', $arp->id,) !!}
        <input type="hidden" name="arp" :value="idArp">
        {!! Form::hidden('brandsNumber', sizeof($brands)) !!}
        <div class="modal-content" class="animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="fa fa-barcode"></i> Agregar nuevo servicio</h3>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <input type="text" name="name" :value="nameService">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="row" style="margin-top: 5px; margin-bottom: 5px">
                        <div class="col-xs-12 col-md-4"> <strong> Marca </strong> </div>
                        <div class="col-xs-12 col-md-4"> <strong> Volumen </strong> </div>
                        <div class="col-xs-12 col-md-4"> <strong> Valor DKK </strong> </div>
                    </div>
                    <div class="row" v-for="(input, index) in arpData">
                        <div class="col-xs-12 col-md-4">
                            <div style="padding-top: 15px; text-transform:uppercase">
                                @{{ input.brand }}
                                <input type="hidden" name="brands[]" :value="input.idBrand">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-area-chart" aria-hidden="true"></i>
                                </div>
                                <input type="text" name="volume[]" :value="input.volume">
                            </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd" aria-hidden="true"></i>
                                </div>
                                <input type="text" name="value[]" :value="input.valueCop">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-divider"></div>
                <div class="col-xs-12">
                    <div class="btns-modal">
                        <div class="datos-btn">
                            {{ Form::button('<i class="fa  fa-plus"></i> Agregar servicio', ['type' => 'submit', 'class'=> 'btn btn-default pull-right' ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
