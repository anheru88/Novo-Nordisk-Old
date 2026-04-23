<div class="modal fade " id="modal-pbcedit">
    <div class="modal-dialog modal-md">
        {!! Form::open(['route' => ['pbcArp.update', 'id' => $arp->id], 'method'=>'POST', 'files' => 'false']) !!}
        <input class="form-control" type="hidden" name="arp_id" :value="idArp">
        <div class="modal-content" class="animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="fa fa-barcode"></i> Modificar PBC de @{{ nameService }}</h3>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="row" style="margin-top: 5px; margin-bottom: 5px">
                        <div class="col-xs-12 col-md-6"> <strong> Marca</strong> </div>
                        <div class="col-xs-12 col-md-6"> <strong>PBC</strong> </div>
                    </div>
                    <div class="row">
                        <div class="row" v-for="(input, index) in arpData">
                            <div class="col-xs-12 col-md-6">
                                <div style="padding-top: 15px; text-transform:uppercase">
                                    @{{ input.brand }}
                                    <input type="hidden" name="brands[]" :value="input.idBrand">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-usd" aria-hidden="true"></i>
                                    </div>
                                    <input type="text" name="pbc[]" :value="input.pbc">
                                </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="content-divider"></div>
                <div class="col-xs-12">
                    <div class="btns-modal">
                        <div class="datos-btn">
                            {{ Form::button('Actualizar PBC', ['type' => 'submit', 'class'=> 'btn btn-default pull-right'
                            ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
