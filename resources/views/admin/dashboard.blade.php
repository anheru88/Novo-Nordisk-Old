@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app">
        <section class="content-header" style="padding: 20px 50px">
            <div class="title-section row">
                <h1>
                    Novo Nordisk - CAM Tool - v 1.2
                </h1>
            </div>
        </section>
        <div class="content" style="padding: 0px 50px">
            <!-- Main content -->
            <section>
                <!-- Info boxes -->
                <div class="row font-Monserrat">
                    @can('cotizaciones.index')
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="info-box-access" onclick="location.href='{{ route('cotizaciones.index') }}'">
                            <span class="info-box-icon icon-round bg-blue"><i class="fas fa-file-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Cotizaciones</span>
                                <span class="info-box-number">{{ getCountAllQuota() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="info-box-access"
                            onclick="location.href='{{ route('cotizaciones.filter', ['estado' => 1, 'quantity' => 20])  }}'">
                            <span class="info-box-icon icon-round bg-yellow">
                                <i class="fas fa-hand-holding-usd"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text-dos">Cotizaciones Pendientes</span>
                                <span class="info-box-number">{{ getCount(5) }}</span>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('negociaciones.index')
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="info-box-access" onclick="location.href='{{ route('negociaciones.index') }}'">
                            <span class="info-box-icon icon-round bg-teal-active"><i
                                    class="fas fa-briefcase"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Negociaciones</span>
                                <span class="info-box-number"></span>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('files.index')
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="info-box-access" onclick="location.href='{{ route('files.index') }}'">
                            <span class="info-box-icon icon-round bg-purple"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text-dos">Repositorio de documentos</span>
                                <span class="info-box-number"></span>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('clients.index')
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="info-box-access" onclick="location.href='{{ route('clients.index') }}'">
                            <span class="info-box-icon icon-round bg-light-blue-active"><i
                                    class="fas fa-building"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Clientes</span>
                                <span class="info-box-number"></span>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('products.index')
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="info-box-access" onclick="location.href='{{ route('products.index') }}'">
                            <span class="info-box-icon icon-round bg-light-blue-active"><i
                                    class="fas fa-syringe"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Productos</span>
                                <span class="info-box-number"></span>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @if (Auth::user()->is_authorizer == 1)
                    @can('autorizaciones.index')
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="info-box-access" onclick="location.href='{{ route('autorizaciones.index') }}'" >
                            <span class="info-box-icon icon-round bg-light-blue-active"><i class="fas fa-pen-nib"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text-dos">Autorizaciones pendientes</span>
                                <span class="info-box-number">{{ getCountAuth() }}</span>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @endif
                    @can('preautorizacion')
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="info-box-access" onclick="location.href='{{ route('preautorizaciones.index') }}'">
                            <span class="info-box-icon icon-round bg-light-blue-active"><i class="fas fa-thumbtack"></i></span>
                            <div class="info-box-content" >
                                <span class="info-box-text-dos">Pre-autorizaciones pendientes</span>
                                <span class="info-box-number">{{ getCountPre() }}</span>
                            </div>
                        </div>
                    </div>
                    @endcan
                </div>
                <!-- /.row -->
            </section>
        </div>
    </div>
</div>
<!-- /.content -->
@endsection
