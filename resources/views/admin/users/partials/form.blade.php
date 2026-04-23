<div class="col-xs-12">
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
    <div class="box box-info quot">
        <div class="box-body">
            <h3 class="box-title"><i class="fa fa-edit"></i> Datos básicos</h3>
            <div class="content-divider"></div>
            <div class="container-fixed">
                <div class="row quot-first-data">
                    <div class="col-xs-6">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Nombre del usuario</div>
                            <div class="quot-data-box-content {{ $errors->has('name') ? ' has-error' : '' }}">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    {{ Form::text('name', null, ['class' => 'form-control', 'id'=>'name','autocomplete'
                                    => 'off']) }}
                                </div>
                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Email</div>
                            <div class="quot-data-box-content {{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    {{ Form::text('email', null, ['class' => 'form-control', 'id'=>'email']) }}
                                </div>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Nickname</div>
                            <div class="quot-data-box-content {{ $errors->has('nickname') ? ' has-error' : '' }}">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-address-book"></i>
                                    </div>
                                    {{ Form::text('nickname', null, ['class' => 'form-control', 'id'=>'nickname']) }}
                                </div>
                                @if ($errors->has('nickname'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nickname') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Telefóno</div>
                            <div class="quot-data-box-content {{ $errors->has('phone') ? ' has-error' : '' }}">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone-square"></i>
                                    </div>
                                    {{ Form::text('phone', null, ['class' => 'form-control', 'id'=>'phone']) }}
                                </div>
                                @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Cargo</div>
                            <div class="quot-data-box-content {{ $errors->has('position') ? ' has-error' : '' }}">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-tie"></i>
                                    </div>
                                    {{ Form::text('cargo', null, ['class' => 'form-control', 'id'=>'cargo']) }}
                                </div>
                                @if ($errors->has('cargo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('cargo') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Dirección</div>
                            <div class="quot-data-box-content {{ $errors->has('address') ? ' has-error' : '' }}">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-address-card"></i>
                                    </div>
                                    {{ Form::text('address', null, ['class' => 'form-control', 'id'=>'address']) }}
                                </div>
                                @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Contraseña</div>
                            <div class="quot-data-box-content {{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                    {{ Form::password('password', null, ['class' => 'form-control', 'id'=>'password'])
                                    }}
                                </div>
                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Confirmar contraseña</div>
                            <div class="quot-data-box-content">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                    {{ Form::password('password_confirmation', null, ['class' => 'form-control',
                                    'id'=>'password-confirm']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Firma</div>
                            <div class="quot-data-box-content {{ $errors->has('firm') ? ' has-error' : '' }}">
                                <div class="input-group">
                                    {!! Form::file ('firm', ['class'=>'files', 'type'=>'file', 'enctype' =>
                                    'multipart/form-data']) !!}
                                </div>
                                @if ($errors->has('firm'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('firm') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12">
    <div class="box box-info quot">
        <div class="box-body">
            <h3 class="box-title"><i class="fa fa-unlock-alt"></i> Permisos de acceso</h3>
            <div class="content-divider"></div>
            <div class="row quot-first-data">
                <div class="col-xs-8">
                    <div class="quot-data-box">
                        <div class="quot-data-box-title">Seleccione un tipo de permiso</div>
                        <div class="quot-data-box-content">
                            <ul class="list-unstyled">
                                @foreach ($roles as $role)
                                <li>
                                    <label class="checkbox-container">
                                        @if ($role->name == "Autorizador")
                                        {{ Form::checkbox('roles[]', $role->id, null, ['v-on:click' => 'levelAuth =
                                        !levelAuth']) }}
                                        <input type="hidden" name="auth" value="1">
                                        @else
                                        {{ Form::checkbox('roles[]', $role->id, null) }}
                                        <input type="hidden" name="auth" value="0">
                                        @endif
                                        {{ $role->name }}
                                        <span class="checkmark"></span>
                                        <em>({{ $role->description ?: 'Sin descripción'}})</em>
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @if ($errors->has('roles'))
                        <span class="help-block">
                            <strong>{{ $errors->first('roles') }}</strong>
                        </span> @endif
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="quot-data-box" v-if="levelAuth">
                        <div class="quot-data-box-title">Seleccione un nivel de autorización</div>
                        <div class="quot-data-box-content">
                            <div class="input-group">
                                @foreach($discLevels as $key => $discLevel )
                                <div class="level">
                                    <label class="radiobtn">{{ $discLevel->disc_level_name }}
                                        <input type="radio" value="{{ $discLevel->id_disc_level }}"
                                            name="id_level_discount" @if($key==0) {{ "checked" }} @endif>
                                        <span class="checkradio"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="quot-send-btn">
    {{ Form::submit('Guardar', ['class'=> 'btn btn-novo-big' ]) }}
</div>
