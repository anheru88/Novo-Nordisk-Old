@extends('auth.layout') 
@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Sistema</b>Laravel</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Registro en el sistema</p>

    <form action="registro" method="post">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

      <div class="form-group has-feedback">
        <label>nombre</label>
        <input type="text" class="form-control" name="name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>


      <div class="form-group has-feedback">
        <label>email</label>
        <input type="email" class="form-control" name="email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <label>password</label>
        <input type="password" class="form-control" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@endsection