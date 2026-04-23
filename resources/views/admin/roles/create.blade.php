@extends('admin.layout') 
@section('content')
<div class="content-wrapper">
        @include('admin.layouts.breadcrumbs')
<section class="content-header">
    <div class="bread-crumb">
        <a href="/">Inicio</a> / <a href="{{ route('roles.index') }}">Administrador de roles</a> / Crear nuevo rol
    </div>
    <h1>
        Crear nuevo rol
    </h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-info quot">
        <!-- /.box-header -->
        {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}

            @include('admin.roles.partials.form')

        {!! Form::close() !!}
</section>
</div>
<!-- /.content -->
@endsection