@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="users" id="users">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="/">Inicio</a> / <a href="{{ route('users.index') }}">Administrador de usuarios</a> / Crear nuevo usuario
            </div>
            <h1>
            Crear nuevo usuario
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- /.box-header -->
            {!! Form::open(['route' => 'users.store', 'method' => 'POST', 'autocomplete' => 'off', 'files' => 'true', 'enctype' => 'multipart/form-data']) !!}
            @include('admin.users.partials.form')
            {!! Form::close() !!}
        </section>
    </div>
</div>
<!-- /.content -->
@endsection
@section('pagescript')
<script src="{{ asset('js/user.js') }}" ></script>
@endsection
