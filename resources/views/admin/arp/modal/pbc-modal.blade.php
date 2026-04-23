<div class="modal fade " id="modal-pbc">
    <div class="modal-dialog modal-md">
        {!! Form::open(['route' => ['pbcArp.store', 'id' => $arp->id], 'method'=>'POST', 'files' => 'false']) !!}
        <input class="form-control" type="hidden" name="arp_id" :value="idArp">
        <div class="modal-content" class="animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="fa fa-barcode"></i> Agregar PBC a  @{{ nameService }}</h3>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="row" style="margin-top: 5px; margin-bottom: 5px">
                        <div class="col-xs-12 col-md-6"> <strong> Marca</strong> </div>
                        <div class="col-xs-12 col-md-6"> <strong>PBC</strong>  </div>
                    </div>
                    <div class="row">
                        @foreach ($brands as $key => $brand)
                        <div class="col-xs-12 col-md-6">
                            <div style="padding-top: 15px; text-transform:uppercase">
                                {{ $brand->brand_name }}
                                {!! Form::hidden('brands[]'.$key, $brand->id_brand,) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd" aria-hidden="true"></i>
                                </div>
                                {!! Form::text('pbc[]'.$key, null, ['class' => 'form-control','placeholder' => 'PBC', 'autocomplete' => 'off']) !!}
                            </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="content-divider"></div>
                <div class="col-xs-12">
                    <div class="btns-modal">
                        <div class="datos-btn">
                            {{ Form::button('Agregar PBC', ['type' => 'submit', 'class'=> 'btn btn-default pull-right' ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
