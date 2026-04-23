<div class="discounTab" v-if="asistida">
    <div class="col-xs-12 col-sm-6">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="form-group strong">Concepto<span class="required">*</span></div>
                <div class="form-group">
                    <select name="concepto" id="conceptoIn" class="form-control focus filter-table-textarea"
                        v-on:change="addProduct">
                        <option value="0">ESCALAS</option>
                        @foreach ($concepts as $key => $concept)
                        <option value="{{ $key }}">{{ $concept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="content-divider"></div>
    <div class="col-xs-12 col-sm-6" v-if="concept">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="col-xs-12 no-padding">
                    <div class="form-group strong">Sujeto a cumplimiento de volumen <span class="required"></span>
                    </div>
                    <div class="form-group">
                        <label class="radiobtn">NO
                            <input type="radio" value="no" name="volumen" checked>
                            <span class="checkradio"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="col-xs-12 no-padding">
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
                            {{ Form::number('descuento', null, ['id'=>'desc','class' => 'form-control', 'v-model' =>
                            'discount']) }}
                            <div class="input-group-addon">
                                <i class="fa fa-percentage"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6" v-if="concept">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
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
                        'rows' => 2, 'v-model' => 'obs_concept']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="quot-charge">
            <button class="btn btn-success btn-sm" v-on:click="negociacionAsistida('products')" v-if="btnAsistidaEscala"
                type="button">
                <i class="fas fa-magic"></i> AGREGAR PRODUCTOS
            </button>
            <button class="btn btn-success btn-sm" v-on:click="negociacionAsistidaxConcepto('products')"
                v-if="btnAsistidaConcepto" type="button">
                <i class="fas fa-magic"></i> AGREGAR PRODUCTOS
            </button>
        </div>
    </div>
</div>
