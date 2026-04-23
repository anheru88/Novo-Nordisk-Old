@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        @include('admin.layouts.breadcrumbs')
        <div class="quotation">
            <section class="content-header">
                <div class="bread-crumb">
                    <a href="{{ route('home') }}">Inicio</a> / Reportes
                </div>
                <h1>
                    Generación de Informes
                </h1>
            </section>
            <!-- Main content -->
            {!! Form::open(['route' => ['reportes.search'], 'method' => 'POST', 'files' => 'false']) !!}
            <section class="content">
                <div class="box box-info">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <section class="row" id="app">
                            <div class="container-fixed">
                                <div class="col-xs-12">
                                    <div class="form-data-box">
                                        <div class="form-data-box-title">
                                            Selecciona los criterios de busqueda del informe
                                        </div>
                                        <div class="col-xs-12 col-sm-3 no-padding-left">
                                            <div class="form-group">
                                                <label>Tipo de informe</label>
                                                <div class="input-group">
                                                    <input type="text" name="type" id="type1" hidden>
                                                    <select name="type" id="type" class="form-control"
                                                        @change="onChange($event)" required>
                                                        <option value="">Seleccione un tipo</option>
                                                        <option value="cli">Clientes</option>
                                                        <option value="cot">Cotizaciones</option>
                                                        <option value="neg">Negociaciones</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <section class="row">
                                <div class="container-fixed">
                                    <div class="col-xs-12" v-if="selectTypeClient">
                                        @include('admin.reports.modals.modal_Client')
                                    </div>
                                </div>
                            </section>
                            <section class="row">
                                <div class="container-fixed">
                                    <div class="col-xs-12" v-if="selectTypeCotOrNeg">
                                        @include('admin.reports.modals.modal_CotOrNeg')
                                    </div>
                                </div>
                            </section>
                        </section>
                    </div>
                    <!-- /.box-body -->
                </div>
            </section>
            <section class="content">
                <div class="box box-info">
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if (isset($results) && !empty($results))
                            <div class="row">
                                <section class="col-xs-12 col-sm-12 connectedSortable">
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"> <i class="fa fa-search"></i> Resultados de
                                                busqueda</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="overflow">
                                                @include('admin.reports.tablereport',$results)
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        @elseif (isset($results1) && !empty($results1))
                            <div class="row">
                                <section class="col-xs-12 col-sm-12 connectedSortable">
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"> <i class="fa fa-search"></i> Resultados de
                                                busqueda</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="overflow">
                                                @include('admin.reports.tablereportneg',$results1)
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        @elseif (isset($results2) && !empty($results2))
                            <script>
                                this.selectTypeClient = true
                            </script>
                            <div class="row">
                                {{-- v-if="!selectTypeClient" --}}
                                <section class="col-xs-12 col-sm-12 connectedSortable">
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"> <i class="fa fa-search"></i> Resultados de
                                                busqueda</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="overflow">
                                                @include('admin.reports.tablereportcli',$results2)
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
            </section>
            {!! Form::close() !!}
        </div>
    </div>
    <!-- /.content -->
@endsection
@section('pagescript')
    @if (isset($type))
        {{-- <script>
    $(document).ready(function() {
        $('#type').val('{{$type}}');
        $('#type1').val('{{$type}}');
        $('#usuario').val('{{$idUser}}');
        $('#typeclient').val('{{$idClientType}}');
        $('#id_department').val('{{$idDepartment}}');
        // $('#id_city').val('{{$idCity}}');
        $('#payterm').val('{{$idPayterm}}');
        $('#active').val('{{$active}}');
        $('#canal').val('{{$idChannel}}');
    });
</script> --}}
    @endif
    <script src="{{ asset('js/reports.js') }}"></script>
    <script>
        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
@endsection
