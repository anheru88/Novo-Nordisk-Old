@extends('admin.layout') 
@section('content')
<div class="content-wrapper">
        @include('admin.layouts.breadcrumbs')
<section class="content-header">
    <div class="bread-crumb">
        <a href="/">Inicio</a> / <a href="{{ route('users.index') }}">Administrador de usuarios</a> / {{ $user->name }}
    </div>
    <h1>
        Editar usuario
    </h1>
</section>
<!-- Main content -->
<section class="content">

</section>
</div>

<!-- /.content -->
@endsection