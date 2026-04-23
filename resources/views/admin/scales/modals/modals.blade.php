<!-- Modal para agregar escalas -->
<div class="modal fade" id="modal-escala">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title"><i class="fas fa-chart-bar"></i> Agregar nueva escala</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group strong">Escriba el nombre de la escala<span class="required">*</span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-chart-bar"></i></div>
                                {{ Form::text('nombre_escala', null, ['required','id'=>'scalename','class' =>
                                'form-control','v-model' => 'scaleName']) }}
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-xs-12">
                        <div class="form-group strong">Canal de la escala<span class="required">*</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-indent"></i></div>
                                {!! Form::select('id_channel',$channels, 'selected',['class'=> 'form-control', 'placeholder' => 'Seleccione el canal', 'id' => 'id_channelN']) !!}
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="content-divider"></div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group strong">Niveles de la escala</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <div class="form-group"><strong>Descuento</strong><span class="required">*</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-percentage"></i></div>
                                {{ Form::number('porcentaje', 0, ['required','class' => 'form-control','v-model' =>
                                'porcentaje']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 no-padding-left">
                        <div class="form-group"><strong>Piso</strong><span class="required">*</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                {{ Form::number('piso', 1, ['required','class' => 'form-control','v-model' => 'piso'])
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 no-padding-left">
                        <div class="form-group"><strong>Techo</strong><span class="required">*</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                {{ Form::text('techo', 1, ['required','class' => 'form-control','v-model' => 'techo',
                                'disabled']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 no-padding-left">
                        <div class="form-group">&nbsp;</div>
                        <div class="form-group">
                            <button id="add-row" class="btn btn-sm btn-info" v-on:click="addNivel"><i
                                    class="fas fa-plus"></i> Agregar nivel</button>
                        </div>
                    </div>
                </div>
                <div class="repeat_field">
                    <table class="table table-hover" id="repeatable-fieldset-one">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Porcentaje</th>
                                <th class="text-center">Piso</th>
                                <th class="text-center">Techo</th>
                                <th class="text-center">Unidades</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(input, index) in scalesModal" :id="'prod'+index" class="animated">
                                <td>@{{ index + 1 }}</td>
                                <td>
                                    @{{ input.porcentaje }}%<input type="hidden" name="porcentaje[]"
                                        :value="input.productId">
                                </td>
                                <td class="text-center">
                                    @{{ input.piso }}<input type="hidden" name="piso[]" :value="input.quantity">
                                </td>
                                <td class="text-center">
                                    @{{ input.techo }}<input type="hidden" name="techo[]" :value="input.quantity">
                                </td>
                                <td class="text-center">
                                    @{{ prodUnitText }}<input type="hidden" name="units[]" :value="prodUnitID">
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-danger" v-on:click="removeProduct(index)">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <!-- empty hidden one for jQuery -->
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default btn-novo pull-right" v-on:click="saveScales">Guardar
                    escala</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Modal para editar escalas -->
<div class="modal fade" id="modal-escala-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title"><i class="fas fa-chart-bar"></i> Editar la escala @{{ scaleName }}</h3>
                {{ Form::hidden('old_escala', null, ['required','id'=>'scale','class' => 'form-control','v-model' =>
                'scaleNameOld']) }}
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group strong">Nombre de la escala<span class="required">*</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-chart-bar"></i></div>
                                {{ Form::text('nombre_escala', null, ['required','id'=>'desc','class' =>
                                'form-control','v-model' => 'scaleName']) }}
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-xs-12">
                        <div class="form-group strong">Canal de la escala<span class="required">*</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-indent"></i></div>
                                {!! Form::select('id_channel',$channels, null,['class'=> 'form-control', 'placeholder' => 'Seleccione el canal','required', 'id' => 'id_channel']) !!}
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="content-divider"></div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group strong">Niveles de la escala</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <div class="form-group"><strong>Descuento</strong><span class="required">*</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-percentage"></i></div>
                                {{ Form::number('porcentaje', 0, ['required','class' => 'form-control','v-model' =>
                                'porcentaje']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 no-padding-left">
                        <div class="form-group"><strong>Piso</strong><span class="required">*</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                {{ Form::number('piso', 1, ['required','class' => 'form-control','v-model' => 'piso'])
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 no-padding-left">
                        <div class="form-group"><strong>Techo</strong><span class="required">*</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                {{ Form::text('techo', 1, ['required','class' => 'form-control','v-model' => 'techo',
                                'disabled']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 no-padding-left">
                        <div class="form-group">&nbsp;</div>
                        <div class="form-group">
                            <button id="add-row" class="btn btn-sm btn-info" v-on:click="addNivel"><i
                                    class="fas fa-plus"></i> Agregar nivel</button>
                        </div>
                    </div>
                </div>
                <div class="repeat_field">
                    <table class="table table-hover" id="repeatable-fieldset-one">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Porcentaje</th>
                                <th>Piso</th>
                                <th>Techo</th>
                                <th>Unidades</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(input, index) in scalesModal" :id="'prod'+index" class="animated">
                                <td>@{{ index + 1 }}</td>
                                <td>
                                    @{{ input.porcentaje }}%<input type="hidden" name="porcentaje[]"
                                        :value="input.productId">
                                </td>
                                <td>
                                    @{{ input.piso }}<input type="hidden" name="piso[]" :value="input.quantity">
                                </td>
                                <td>
                                    @{{ input.techo }}<input type="hidden" name="techo[]" :value="input.quantity">
                                </td>
                                <td>
                                    @{{ prodUnitText }}<input type="hidden" name="units[]" :value="prodUnitID">
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-danger" v-on:click="removeProduct(index)">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <!-- empty hidden one for jQuery -->
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default btn-novo pull-right" v-on:click="updateScales">Guardar escala</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
