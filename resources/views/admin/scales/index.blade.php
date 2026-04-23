@extends('admin.layout')
@section('content')
<div class="content-wrapper" id="app">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header">
        <div class="bread-crumb">
            <a href="{{  route('home') }}">Inicio</a> / Escalas
        </div>
        <h1>
            Escalas
        </h1>
        <div class="tools-header">
            <div class="tools-menu-btn-icon">Listado de escalas actuales</div>
            @can('products.create')
            <div class="tools-menu-btn">
                <div class="tools-menu-btn-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="tools-menu-btn-text" data-toggle="modal" data-target="#modal-upload"> Carga masiva</div>
            </div>
            @endcan
        </div>
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
        <div class="content-divider"></div>
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fas fa-syringe"></i> Productos</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>Seleccione uno de los productos</label>
                            {!! Form::select('id_producto',$productos, null,['class'=> 'form-control focus
                            filter-table-textarea', 'placeholder' => 'Seleccione',
                            'required','v-on:change'=>'getScales']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8">
                <div class="box box-info" >
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fas fa-chart-bar"></i> Escalas del producto @{{ nameProduct }} </h3>
                        <!-- /.box-tools -->
                    </div>
                    <div class="box-body" v-if="showScales">
                        <div class="no-scales-msg" v-if="noScalesMsg">Este producto no tiene escalas asignadas.</div>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="level-box" v-for="(scale, index) in scales">
                                <div class="level">
                                    <a data-toggle="collapse" data-parent="#accordion" :href="'#collapse'+index" aria-expanded="true" class="">
                                        <div class="title-lvl"><i class="more-less fas fa-caret-right"></i> Escala @{{ scale.scale_number }} </div>
                                    </a>
                                    @can('scales.edit')
                                    <div class="edit-combo">
                                        <button class="btn btn-xs btn-warning" v-on:click="editScales(scale.id_scale)" data-toggle="modal" data-target="#modal-escala-edit"><i class="fas fa-pen"></i></button>
                                        <button class="btn btn-xs btn-danger" v-on:click="removeScale(scale.id_scale)"> <i class="fas fa-trash-alt"></i></button>
                                    </div>
                                    @endcan
                                </div>
                                <div :id="'collapse'+index" class="panel-color panel-collapse collapse" aria-expanded="true" >
                                    <div class="level-body">
                                        <table id="informe" class="table table-striped table-hover">
                                            <tr v-for="(level, index) in scales[index].scalelvl">
                                                <td> @{{ level.scale_discount }}% | @{{ level.scale_min }} - @{{ level.scale_max }} @{{ prodUnitText }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @can('scales.edit')
                        <div class="col-xs-12 no-padding">
                            <div class="level-btn">
                                <div class="tools-menu-btn" data-toggle="modal" data-target="#modal-escala" v-on:click="addScale">
                                    <div class="tools-menu-btn-icon"><i class="fas fa-plus"></i></div>
                                    <div class="tools-menu-btn-text" > Agregar escala</div>
                                </div>
                            </div>
                        </div>
                        @endcan
                    </div>
                    <div class="box-body" v-else>
                        Seleccione un producto de la lista para ver sus escalas.
                    </div>
                </div>
            </div>
    </section>

   @include('admin.scales.modals.modals')
</div>
@endsection

@section('pagescript')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/scales.js') }}"></script>
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

    function toggleIcon(e) {
        $(e.target)
            .prev('.level')
            .find(".more-less")
            .toggleClass(' fa-caret-down fa-caret-right ');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);


    jQuery(document).ready(function($) {
			$('#add-row').on('click', function() {
				var row = $('.empty-row.screen-reader-text').clone(true);
				row.removeClass('empty-row screen-reader-text');
				row.insertBefore('#repeatable-fieldset-one tbody>tr:last');
				return false;
			});
			$('.remove-row').on('click', function() {
				$(this).parents('tr').remove();
				return false;
			});
			$('#repeatable-fieldset-one tbody').sortable({
				opacity: 0.6,
				revert: true,
				cursor: 'move',
				handle: '.sort'
			});
		});


</script>
@endsection

