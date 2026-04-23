<div class="form-data-box">
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>Estado</label>
            <div class="input-group">
                <select id="id_status" name="id_status"
                    class="form-control focus filter-table-textarea">
                    <option value="" selected disabled> - Seleccione - </option>
                    @foreach ($status as $state)
                        <option value="{{ $state->status_id }}">
                            {{ $state->status_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
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
            <label>Producto</label>
            <div class="input-group">
                {!! Form::select('product', $products , null,['id' => 'producto',
                'class'=>'form-control','placeholder' => 'Seleccione el producto']) !!}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>Desde</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                {{ Form::date('desde', null, array('id'=>'quota_date_ini','class' => 'form-control')) }}
                <div class="input-group-addon red-button">
                    <i class="fa fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>Hasta</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                {{ Form::date('hasta', null, array('id'=>'product_date_end1','class' => 'form-control')) }}
                <div class="input-group-addon red-button">
                    <i class="fa fa-times-circle"></i>
                </div>
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
    {{-- <div class="col-xs-12 col-sm-4 no-padding-left">
        <div class="form-group">
            <label>Condición de pago</label>
            <div class="input-group">
                {!! Form::select('payterm', $payterms , null,['class'=>'form-control','placeholder' => 'Seleccione']) !!}
            </div>
        </div>
    </div> --}}
    <div class="col-xs-12 col-sm-10 no-padding-left"></div>
    <div class="col-xs-12 col-sm-1 no-padding-left">
        <div class="form-group">
            <label>&nbsp;</label>
            <div class="input-group">
                <div class="btn-report">
                    {!! Form::hidden('excel', null,['v-model'=>'sendCot']) !!}
                    {{ Form::button('<i class="fa  fa-search"></i> Buscar', ['type' => 'submit', 'class'=> 'btn btn-info pull-left', 'v-on:click'=>'sendCot = false' ])}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-1" style="margin-left: -8px;">
        <div class="form-group">
            <label>&nbsp;</label>
            <div class="input-group">
                <div class="btn-report">
                    {{ Form::button('<i class="fa fa-file-excel-o"></i> Exportar', ['type' => 'submit', 'class'=> 'btn btn-success pull-left', 'v-on:click'=>'sendCot = true' ]) }}
                </div>
            </div>
        </div>
    </div>
</div>