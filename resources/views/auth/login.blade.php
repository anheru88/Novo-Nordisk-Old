@extends('auth.layout') 
@section('content')
@php
    $bg = rand(1, 2);
@endphp
<div class="login" style="background-image:url('/images/bgNovo{{ $bg }}.jpg')">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-md-5">
        <div class="login-title">
            Novo Nordisk - CAM Tool 
        </div>
        <div class="login-box">
          <div class="login-box-body">
            <div class="login-box-msg">Escriba su usuario y contraseña</div>
            <div class="login-form">
              <form  method="post" action="{{ route('login') }}" autocomplete="false">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                  <input type="text" class="form-control" name="email">
                  <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                  <input type="password" class="form-control" name="password">
                  <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <div class="forgot">
                      <a href="#">¿Olvido su contraseña?</a><br>
                    </div>
                    <div class="checkbox icheck">
                      <label><input type="checkbox"> Recordar mis datos</label>
                    </div>
                  </div>
                  <div class="col-xs-12">
                    <button type="submit" class="button">Ingresar</button>
                  </div>
                </div>
                @if ($errors->has('email'))
                <div class="centerDiv alertLogin">
                    <span class="alertLogin-text"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{ $errors->first('email') }}</span>
                </div>
                @endif
                @if ($errors->has('password'))
                <div class="centerDiv alertLogin">
                    <span class="alertLogin-text"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{ $errors->first('password') }}</span>
                </div>
                @endif

                @if(!empty($errorMsg))
                  <div class="alert alert-danger"> {{ $errorMsg }}</div>
                @endif
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-5"></div>
      <div class="col-xs-12 col-sm-2"></div>
    </div>
  </div>
</div>
@endsection