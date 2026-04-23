<div class="modal fade " id="modal-create">
    <div class="modal-dialog modal-lg">
        {!! Form::open(['route' => 'serviceArp.store', 'method'=>'POST', 'files' => 'false']) !!}
        {!! Form::hidden('arp', $arp->id,) !!}
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
                            {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Nombre del servicio', 'autocomplete' => 'off']) !!}
                        </div>
                        </div>
                </div>
                <div class="col-xs-12">
                    <div class="row" style="margin-top: 5px; margin-bottom: 5px">
                        <div class="col-xs-12 col-md-4"> <strong> Marca </strong> </div>
                        <div class="col-xs-12 col-md-4"> <strong> Volumen </strong> </div>
                        <div class="col-xs-12 col-md-4"> <strong> Valor DKK </strong> </div>
                    </div>
                    <div class="row">
                        @foreach ($brands as $key => $brand)
                        <div class="col-xs-12 col-md-4">
                            <div style="padding-top: 15px; text-transform:uppercase">
                                {{ $brand->brand_name }}
                                {!! Form::hidden('brand-'.$key, $brand->id_brand,) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-area-chart" aria-hidden="true"></i>
                                </div>
                                {!! Form::text('volume-'.$key, null, ['class' => 'form-control'.($errors->has('name') ? 'is-invalid':''),'placeholder' => 'Volumen', 'autocomplete' => 'off']) !!}
                            </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd" aria-hidden="true"></i>
                                </div>
                                {!! Form::text('value-cop-'.$key, null, ['class' => 'form-control'.($errors->has('name') ? 'is-invalid':''),'placeholder' => 'Valor en DKK', 'autocomplete' => 'off']) !!}
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
                            {{ Form::button('<i class="fa  fa-plus"></i> Agregar servicio', ['type' => 'submit', 'class'=> 'btn btn-default pull-right' ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
