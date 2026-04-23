@extends('admin.layout')
@section('content')
<div class="content-wrapper" id="app">
        @include('admin.layouts.breadcrumbs')
<section class="content-header">
    <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / Niveles de Autorización
    </div>
    <h1>
        Niveles de Autorización
    </h1>
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Error!</strong> Revise los campos obligatorios.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(Session::has('info'))
    <div class="alert alert-info">
        {{Session::get('info')}}
    </div>
    @endif
    @if(Session::has('error'))
    <div class="alert alert-warning">
        {{Session::get('error')}}
    </div>
    @endif
    
</section>
<!-- Main content -->
<section class="content">
    @can('levels.edit')
    <!--
    <div class="box box-info">
        <div class="box-body">
            {!! Form::open(['action' => 'AuthLevelController@store', 'method' => 'POST']) !!}
            <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                    <label>Lista de productos</label>
                    <select id="id_product" name="id_product" class="form-control focus filter-table-textarea" required>
                        <option value=""> - Seleccione - </option>
                        @foreach($productos as $product)
                        <option value="{{ $product->id_product }}">{{ $product->prod_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2">
                <div class="form-group">
                    <label>Canal</label>
                    <select id="id_dist_channel" name="id_dist_channel" class="form-control focus filter-table-textarea"
                        required>
                        <option value=""> - Seleccione - </option>
                        @foreach($channels as $channel)
                        <option value="{{ $channel->id_channel }}">{{ $channel->channel_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2">
                <div class="form-group">
                    <label>Porcentaje</label>
                    <div class="input-group">
                        {{ Form::text('discount_value', null, ['class' => 'form-control']) }}
                        <div class="input-group-addon">
                            %
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="quot-data-box-auto">
                    <div class="form-group">
                        <div class="quot-data-box-title"> <strong>Niveles</strong></div>
                        <div class="quot-data-box-content">
                            @foreach($discLevels as $discLevel)
                            <label class="radiobtn">{{ $discLevel->disc_level_name }}
                                <input type="radio" value="{{ $discLevel->id_disc_level }}" name="id_level_discount">
                                <span class="checkradio"></span>
                            </label>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="quot-send">
                    <input type="submit" value="Modificar" class="btn btn-novo-big">
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="content-divider"></div>
    -->
    @endcan
    <div class="box box-info">
        <!-- /.box-header -->
        <div class="box-body">
            <div class="body-table">
                @if($prodLevels->count())
                    <div class="col-xs-4 blue-table"></div>
                    <div class="col-xs-4 txt-center blue-table">COMERCIAL</div>
                    <div class="col-xs-4 txt-center blue-table">INSTITUCIONAL</div>
                    <div class="col-xs-4"><label>PRODUCTO</label></div>
                    <div class="col-xs-4 txt-center">
                            @foreach($discLevels as $discLevel)
                            <div class="col-xs-3 txt-center">
                                <label>{{ $discLevel->disc_level_name }}</label>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-xs-4 txt-center">
                            @foreach($discLevels as $discLevel)
                            <div class="col-xs-3 txt-center">
                                <label>{{ $discLevel->disc_level_name }}</label>
                            </div>
                            @endforeach</div>
                    @foreach($productos as $key => $product)
                        <div class="table-div <?php if ($key % 2 == 0) { echo 'gray-table';} ?>">
                        <div class="col-xs-12 col-sm-4">{{ $product->prod_name }}</div>
                            <div class="col-xs-4">
                            @foreach($discLevels as $discLevel)   
                                <div class="col-xs-3 txt-center">
                                    <div class="row">
                                        @php
                                        $level = $discLevel->id_disc_level;
                                        $prod_id = $product->id_product;
                                        $id_channel = $channels[0]->id_channel;
                                        $result = App\Http\Controllers\AuthLevelController::setLevel($prod_id ,$level,$id_channel);
                                        //echo $result[0]->discount_value
                                        if(count($result)>0){
                                        echo $result[0]->discount_value."%";
                                        }
                                        else{
                                        echo "no existe";
                                        }
                                        @endphp
                                    </div>
                                </div>
                            @endforeach
                            </div>
                            <div class="col-xs-4 txt-center">
                            @foreach($discLevels as $discLevel)
                                <div class="col-xs-3 txt-center">
                                    <div class="row">
                                        @php
                                        $level = $discLevel->id_disc_level;
                                        $prod_id = $product->id_product;
                                        $id_channel = $channels[1]->id_channel;;
                                        $result = App\Http\Controllers\AuthLevelController::setLevel($prod_id ,$level, $id_channel);
                                        //echo $result[0]->discount_value
                                        if(count($result)>0){
                                        echo $result[0]->discount_value."%";
                                        }
                                        else{
                                        echo "no existe";
                                        }
                                        @endphp
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                <div class="col-xs-12">No hay registros</div>
                @endif

            </div>
            <!-- PAGINACION -->
        </div>
        <!-- /.box-body -->
    </div>
</section>

<div class="modal fade" id="modal-upload">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['route' => 'prices.masive', 'method' => 'POST','files'=>'true']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="ion ion-ios-search-strong"></i> Carga masiva de precios</h3>
            </div>
            <div class="modal-body">
                    <div class="form-group">Seleccione el archivo que desea cargar
                        <a href="{{ URL::to('/') }}/downloads/carga_masiva_precios.xlsx">Descargar plantilla</a>
                    </div>
                    <div class="quot-data-box-content">
                        <div class="file-loading">
                            {!! Form::file ('doc', ['class'=>'file', 'type'=>'file','id'=>'file-1', 'data-show-preview'=>'false']) !!}
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default btn-novo pull-right">Cargar</button>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

</div>
@endsection

@section('pagescript')
<script>
    $("#file-1").fileinput({
        allowedFileExtensions: ['xls','xlsx'],
        browseClass: "btn btn-primary btn-block",
        maxFileSize: 4000,
        maxFileCount: 1,
        showUpload: false,
        showRemove: true,
        showCaption: true,
        browseOnZoneClick: false,
        showBrowse: true,
        showDrag:false,
        uploadUrl: '#',
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });

</script>
@endsection