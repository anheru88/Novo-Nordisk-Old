<div class="form-data-box">
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>CAM</label>
            <div class="input-group">
                <select name="user" id="usuario" class="form-control">
                    <option value="">Seleccione un cam</option>
                    @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>Canal</label>
            <div class="input-group">
                {!! Form::select('channel', $channels , null,['id'=>'canal',
                'class'=>'form-control','placeholder' => 'Seleccione']) !!}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>Tipo de cliente</label>
            <div class="input-group">
                {!! Form::select('typeclient', $type_clients, null,['id'=>'typeclient',
                'class'=>'form-control','placeholder' => 'Seleccione']) !!}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>Departamento</label>
            <div class="input-group">
                <select id="id_department" name="id_department" class="form-control focus filter-table-textarea"
                    @change="changeCity()" v-model="department">
                    <option value=""> - Departamento - </option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id_locations }}">
                        {{ $department->loc_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>Ciudad</label>
            <div class="input-group">
                <select id="id_city" name="id_city" class="form-control focus filter-table-textarea" v-model="city">
                    <option :value="undefined"> - Ciudad - </option>
                    <option v-for="city in cities" :value="city.id_locations">
                        @{{city.loc_name}}
                    </option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>Forma de pago</label>
            <div class="input-group">
                <select id="payterm" name="payterm" class="form-control focus filter-table-textarea"
                    @change="getPayForm()">
                    <option value="">Seleccione</option>
                    @foreach($forma_pagos as $forma_pago)
                    <option value="{{ $forma_pago->id_payterms }}">
                        {{ $forma_pago->payterm_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>Estado del cliente</label>
            <div class="input-group">
                {{ Form::select('active',array('1' => 'Activo','0' => 'Inactivo'),null,['class'=>'form-control focus filter-table-textarea', 'id'=>'active', 'placeholder' => 'Selecione un estado']) }}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>&nbsp;</label>
            <div class="input-group">
                <div class="btn-report">
                    {!! Form::hidden('excel', null,['v-model'=>'sendCot']) !!}
                    {{ Form::button('<i class="fa  fa-search"></i> Buscar', ['type' => 'submit', 'class'=> 'btn btn-info pull-left', 'v-on:click'=>'sendCot = false' ])}}
                </div>
                <div class="btn-report">
                    {{ Form::button('<i class="fa fa-file-excel-o"></i> Exportar', ['type' => 'submit', 'class'=> 'btn btn-success pull-left', 'v-on:click'=>'sendCot = true' ]) }}
                </div>
            </div>
        </div>
    </div>
</div>