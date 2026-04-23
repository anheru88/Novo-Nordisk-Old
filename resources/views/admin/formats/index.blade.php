@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="{{  route('home') }}">Inicio</a> / Repositorio de documentos
            </div>
            <h1>
                <i class="fas fa-book"></i> Repositorio de documentos
            </h1>
        </section>
        <section class="content">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row font-Monserrat">
                        @foreach ($ftypes as $ftype)
                        <div class="col-xs-3 col-sm-2">
                            <div class="folder"
                                onclick="location.href='{{ route('formats.types',['format' => $ftype->id_formattype ])  }}'">
                                <div class="folder-icon"><i class="fas fa-file-alt"></i></div>
                                <div class="folder-name"
                                    onclick="location.href='{{ route('formats.types',['format' => $ftype->id_formattype ])  }}'">
                                    <span class="info-box-text-dos">{{ $ftype->format_name }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
