<div class="col-xs-12">
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
                                    {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name',
                                    'autocomplete' => 'off']) }}
                                </div>
                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
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
                                    {{ Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) }}
                                </div>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
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
                                    {{ Form::text('nickname', null, ['class' => 'form-control', 'id' => 'nickname']) }}
                                </div>
                                @if ($errors->has('nick'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nick') }}</strong>
                                </span>
                                @endif
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
                                    {{ Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone']) }}
                                </div>
                                @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                @endif
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
                                    {{ Form::text('address', null, ['class' => 'form-control', 'id' => 'address']) }}
                                </div>
                                @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                                @endif
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
                                    {{ Form::text('position', null, ['class' => 'form-control', 'id'=>'position']) }}
                                </div>
                                @if ($errors->has('position'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('position') }}</strong>
                                </span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="quot-data-box">
                            <div class="quot-data-box-title">Firma</div>
                            @if ($user->firm == "")
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
                            @else
                            <div class="image-page img-responsive" v-if="!changeImage">
                                <img src="{{ asset('/uploads/firms/'.$user->firm) }}" alt="" width="100" height="100">
                                <button class="btn btn-primary mp-3" type="button"
                                    v-on:click="changeImage = true">Cambiar imagen</button>
                            </div>
                            <div class="custom-file" v-if="changeImage">
                                <div class="m-5">
                                    <div class="quot-data-box-content {{ $errors->has('firm') ? ' has-error' : '' }}">
                                        <div class="input-group">
                                            {{ Form::file('firm', ['class' => 'file', 'type' => 'file', 'id' => 'file',
                                            'multiple', 'enctype' => 'multipart/form-data']) }}
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary mp-3" type="button"
                                    v-on:click="changeImage = false">Cancelar</button>
                            </div>
                            @endif

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
                            {{-- @php
                            dd($user->is_authorizer);
                            @endphp --}}
                            @if ($user->is_authorizer == 1)
                            <input type="hidden" name="authorizer" value="1" id="authorizer">
                            @endif
                            <ul class="list-unstyled">
                                @foreach ($roles as $role)
                                <li>
                                    <label class="checkbox-container">
                                        @if ($role->name == 'Autorizador')
                                        {{ Form::checkbox('roles[]', $role->id, null, ['id' => 'rol' . $role->id,
                                        'v-on:change' => 'changeAuth(' . $role->id . ')']) }}
                                        @else
                                        {{ Form::checkbox('roles[]', $role->id, null, ['id' => 'rol' . $role->id]) }}
                                        <input type="hidden" name="is_authorizer" value="0" id="is_authorizer">
                                        @endif
                                        {{ $role->name }}
                                        <span class="checkmark"></span>
                                        <em>({{ $role->description ?: 'Sin descripción' }})</em>
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="quot-data-box" v-if="levelAuth">
                        <div class="quot-data-box-title">Seleccione un nivel de autorización</div>
                        <div class="quot-data-box-content">
                            <div class="input-group">
                                @foreach ($levels as $key => $discLevel)
                                <div class="level">
                                    <label class="radiobtn">{{ $discLevel->disc_level_name }}
                                        {{ Form::radio('authlevel', $discLevel->id_disc_level, $discLevel->id_disc_level
                                        == $user->authlevel ? true : false) }}
                                        <span class="checkradio"></span>
                                        <input type="hidden" name="is_authorizer" value="1" id="is_authorizer">
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
    {{ Form::submit('Guardar', ['class' => 'btn btn-novo-big']) }}
</div>
