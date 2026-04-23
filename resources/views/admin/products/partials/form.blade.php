<div class="modal fade " id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title">Modificar precio vigente de @{{ productNameModal }}</h1>

            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'prices.updatePrice', 'method' => 'POST', 'files' => 'false']) !!}

                <div class="container-fixed">
                    <div id="descripcion_user" class="descripcion_user">
                        <div class="col-sm-12 modal-padding modal-product">
                            <h2> @{{ productNameModal }}</h2>
                            Precio Institucional: $@{{ formatPrice(products.v_institutional_price) }} </br>
                            Precio Comercial: $@{{ formatPrice(products.v_commercial_price) }}</br>
                            Vigencia hasta: @{{ dateFormat(products.prod_valid_date_end) }}
                            <input type="hidden" name="id_productxprices" :value=products.id_productxprices>
                            <input type="hidden" name="id_product" :value=products.id_product>
                        </div>
                        <div class="content-divider"></div>
                        <div class="col-sm-12 modal-padding">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title"><strong>Modificación por regulación</strong></div>
                                <div class="quot-data-box-content">
                                    <label class="radiobtn">SI
                                        <input type="radio" value="si" name="modification_type" checked>
                                        <span class="checkradio"></span>
                                    </label>
                                    <label class="radiobtn">NO
                                        <input type="radio" value="no" name="modification_type">
                                        <span class="checkradio"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 modal-padding">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Nueva vigencia (Desde)</div>
                                <div class="quot-data-box-content">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            {{ Form::date('prod_valid_date_ini', null, ['required','class' => 'form-control', 'id'=>'product_date_ini1']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 modal-padding">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Nueva vigencia (Hasta)</div>
                                <div class="quot-data-box-content">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            {{ Form::date('prod_valid_date_end', null, ['required', 'class' => 'form-control', 'id'=>'product_date_end1']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 modal-padding">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Nuevo precio comercial</div>
                                <div class="quot-data-box-content">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-dollar"></i>
                                        </div>
                                        <input type="text" name="v_commercial_price" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 modal-padding">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Nuevo precio institucional</div>
                                <div class="quot-data-box-content">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-dollar"></i>
                                        </div>
                                        <input type="text" name="v_institutional_price" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 modal-padding">
                            <div class="quot-data-box">
                                <div class="quot-data-box-title">Comentarios (Opcionales)</div>
                                <div class="quot-data-box-content">
                                    {{ Form::textarea('comments', null, ['class' => 'form-control', 'id'=>'address'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="quot-send-btn">
                    {{ Form::submit('Guardar', ['class'=> 'btn btn-novo-big' ]) }}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>