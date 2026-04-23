@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="users" id="users">
        <section class="content-header">
            <div class="bread-crumb">
                <a href="#">Home</a> / <a href="{{ route('users.index') }}">Administrador de usuarios</a> / {{ $user->name }}
            </div>
            <h1>
                Editar usuario
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
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
            {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PUT', 'files' => 'true']) !!}

            @include('admin.users.partials.form_edit')

            {!! Form::close() !!}
        </section>
    </div>
</div>
<!-- /.content -->
@endsection
@section('pagescript')
<script src="{{ asset('js/user.js') }}"></script>

<script>
    $('#file').fileinput({
        allowedFileExtensions: ['jpg', 'png', 'gif','doc','pdf','xls','docx','xlsx'],
        browseClass: "btn btn-primary btn-block",
        maxFileSize: 4000,
        maxFileCount: 10,
        showUpload: false,
        showRemove: false,
        showCaption: false,
        browseOnZoneClick: true,
        showBrowse: false,
        showDrag:true,
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });
</script>

@endsection
