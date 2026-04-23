<div class="box-body">
    <div class="content-divider"></div>
    <div class="container-fixed">
        <div class="row quot-first-data">
            <div class="col-xs-12 col-sm-6 no-padding">
                <div class="quot-data-box">
                    <div class="quot-data-box-title">Nombre del rol</div>
                    <div class="quot-data-box-content">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-user-plus"></i>
                            </div>
                            {{ Form::text('name', null, ['class' => 'form-control', 'id'=>'name']) }}
                        </div>
                    </div>
                </div>
                <div class="quot-data-box">
                    <div class="quot-data-box-title">Slug</div>
                    <div class="quot-data-box-content">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            {{ Form::text('slug', null, ['class' => 'form-control', 'id'=>'slug']) }}
                        </div>
                    </div>
                </div>
                <div class="quot-data-box">
                    <div class="quot-data-box-title">Descripción</div>
                    <div class="quot-data-box-content">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            {{ Form::text('description', null, ['class' => 'form-control', 'id'=>'description']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding">
                <div class="quot-data-box">
                    <div class="quot-data-box-title">Permisos Especiales</div>
                    <div class="quot-data-box-content form-group">
                        <label class="radiobtn">Acceso total
                            {{ Form::radio('special', 'all-access' , false, ['class'=>'']) }}
                            <span class="checkradio"></span>
                        </label>
                        <label class="radiobtn">Ningun acceso
                                {{ Form::radio('special', 'no-access' , false, ['class'=>'']) }}
                                <span class="checkradio"></span>
                            </label>
                    </div>
                </div>
                <div class="quot-data-box">

                    <div class="quot-data-box-title">Permisos</div>
                    <div class="quot-data-box-content form-group">
                        <ul class="list-unstyled">
                            @foreach ($permissions as $permission)
                            <li>
                                <label class="checkbox-container">{{ $permission->name }}
                                    {{ Form::checkbox ('permissions[]', $permission->id, null) }}
                                    <span class="checkmark"></span>
                                    <em>({{ $permission->description ?: 'Sin descripción' }})</em>
                                </label>
                            </li>
                                
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-divider"></div>
<div class="quot-send-btn">
    {{ Form::submit('Guardar', ['class'=> 'btn btn-novo-big' ]) }}
</div>