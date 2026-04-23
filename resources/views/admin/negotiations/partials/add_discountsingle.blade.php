<div class="discounTab" v-if="individual">
    <div class="col-xs-12 col-sm-6 no-padding-left">
        <div class="row">
            <div class="col-xs-12 col-sm-6 ">
                <div class="form-group strong">Producto<span class="required">*</span></div>
                <div class="form-group">
                    <select name="product" id="product" class="form-control focus filter-table-textarea"
                        v-on:change='getProductsClientDesc'>
                        <option value="">-- SELECCIONE --</option>
                        <option v-for="(input, index) in productsArray" :value="input.id_product">
                            @{{ input.productname }} @{{ input.id_quotation }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding-left">
                <div class="form-group strong">Concepto<span class="required">*</span></div>
                <div class="form-group">
                    {!! Form::select('concepto',$concepts, null,['class'=> 'form-control focus
                    filter-table-textarea', 'placeholder' => 'Seleccione', 'id' => 'concepto', ' v-on:change' =>
                    'addProduct']) !!}
                </div>
            </div>
            <div class="content-divider" v-if="concept"></div>
            <div class="col-xs-12 col-sm-6">
                <div class="col-xs-12 no-padding" v-if="concept">
                    <div class="form-group strong">Sujeto a cumplimiento de volumen <span class="required">*</span>
                    </div>
                    <div class="form-group">
                        <label class="radiobtn">SI
                            <input type="radio" value="si" name="volumen" v-on:click="setVolumen">
                            <span class="checkradio"></span>
                        </label>
                        <label class="radiobtn">NO
                            <input type="radio" value="no" name="volumen" v-on:click="setVolumen">
                            <span class="checkradio"></span>
                        </label>
                    </div>
                </div>
                <div class="content-divider" v-if="volumen"></div>
                <div class="col-xs-12 col-sm-4 no-padding" v-if="volumen">
                    <div class="form-group ">Cuanto</div>
                    <div class="form-group">
                        {{ Form::number('cantidad', null, ['class' => 'form-control', 'id'=>'cantidad', 'v-if' =>
                        'volumen'])}}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-8" v-if="volumen">
                    <div class="form-group ">Unidades</div>
                    <div class="form-group">
                        {!! Form::select('unidades',$measure_units, null,['class'=> 'form-control focus
                        filter-table-textarea', 'placeholder' => '-- Seleccione --', 'id' => 'unidades']) !!}
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="col-xs-12 no-padding" v-if="concept">
                    <div class="form-group strong">Tipo de descuento <span class="required">*</span></div>
                    <div class="form-group">
                        <label class="radiobtn">Independiente
                            <input type="radio" value="independiente" name="desctype" v-on:click="discountType">
                            <span class="checkradio"></span>
                        </label>
                        <label class="radiobtn">Convenio
                            <input type="radio" value="convenio" name="desctype" v-on:click="discountType">
                            <span class="checkradio"></span>
                        </label>
                    </div>
                </div>
                <div class="content-divider" v-if="discount_type"></div>
                <div class="col-xs-12 no-padding" v-if="discount_type">
                    <div class="form-group strong">Descuento<span class="required">*</span></div>
                    <div class="form-group">
                        <div class="input-group">
                            {{ Form::number('descuento' ,null, ['id'=>'desc','class' => 'form-control', 'v-on:blur'=>
                            'calcDiscount', 'v-model' => 'discount']) }}
                            <div class="input-group-addon">
                                <i class="fa fa-percentage"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div class="row">
            <div class="col-xs-12 col-sm-4" v-if="concept">
                <div class="form-group strong">Nota aclaratoria</div>
                <div class="form-group">
                    <select name="tiponota" id="tipo_nota" class="form-control focus filter-table-textarea"
                        v-on:change="addNota">
                        <option value="">-- SELECCIONE --</option>
                        <option value="1">NEPS</option>
                        <option value="2">LOGISTICA</option>
                        <option value="3">NUEVO</option>
                    </select>
                </div>
                <div class="form-group subtitle" v-if="newnota">Nueva nota</div>
                <div class="form-group" v-if="newnota">
                    {{ Form::text('nueva_nota', null, ['class' => 'form-control', 'id'=>'new_nota', 'v-model' =>
                    'nota_text'])}}
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 no-padding-left" v-if="addnota">
                <div class="form-group strong">Observaciones</div>
                <div class="form-group">
                    <div class="input-group">
                        {{ Form::textarea('observaciones', null, ['id'=>'observaciones','class' => 'form-control',
                        'rows' => 2]) }}
                    </div>
                </div>
            </div>
            <div class="content-divider" v-if="concept"></div>
            <div class="col-xs-12 ">
                <div class="results-percent">
                    <div class="description">Descuento @{{ discount_text }}</div>
                    <div class="value">@{{ discount }}%</div>
                    <div class="description">Descuento a precio</div>
                    <div class="value">@{{ pay_discount }}%</div>
                    <div class="description">Descuento sobre el precio cotizado</div>
                    <div class="value">@{{ discount_price }}%</div>
                    <div class="description">Total descuento acumulado</div>
                    <div class="value">@{{ discount_acum }}%</div>
                </div>
            </div>
            <div class="content-divider"></div>
            <div class="col-xs-12">
                <div class="quot-charge">
                    <button type="button" class="btn btn-bluegen btn-sm float-right"
                        v-on:click='addProductsNegotiation'><i class="fas fa-plus"></i> Añadir descuento</button>
                </div>
            </div>
        </div>
    </div>
</div>
