@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <section class="content-header">
        <div class="bread-crumb">
            <a href="/">Inicio</a> / <a href="{{ route('clients.index') }}">Clientes </a> / {{ $client->client_name }}
        </div>
        <button class="btn btn-link-white pull-right" data-toggle="modal" data-target="#modal-cetificate">
            <i class="fas fa-file-pdf"></i>
            Crear certificado de cliente
        </button>
        <div style="text-align: left">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                <i class="fas fa-share-alt"></i>
                Compartir documentos
            </button>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['action' => 'FilesController@sharedDocs', 'method' => 'POST']) !!}
                    {{ csrf_field() }}
                    <h2 style="text-align: center"><u> Documentos </u></h2>
                    @foreach($folders as $key => $folder)
                </br>
                    <div class="form-check">
                        <input class="form-check-input" name="id_files[]" type="checkbox" value="{{ $folder->id_files }}" id="flexCheckDefault{{ $folder->id_files }}">
                        <label class="form-check-label" for="flexCheckDefault{{ $folder->id_files }}">{{ $folder->file_name }}</label>
                    </div>
                    @endforeach
                    <div class="box-body">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="destinatario" class="col-form-label" name="destinatario">Destinatario:</label>
                                <input type="text" class="form-control" id="destinatario" name="destinatario"
                                    placeholder="cliente@gmail.com">
                            </div>
                            <div class="form-group">
                                <label for="mensaje" class="col-form-label" name="mensaje">Mensaje:</label>
                                <textarea class="form-control" id="message-text"
                                    placeholder="Adjunto los siguientes documentos" name="mensaje" id="mensaje"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-6 no-padding text-center">
                        <div class="quot-data-box">
                            <div class="quot-data-box-content">
                                <label for="timelast" class="col-form-label">Tiempo de expiración del link</label>
                                <select class="form-select btn btn-primary btn-sm" id="timelast" name="timelast" aria-label="Default select example">
                                    <option selected>Eliga un tiempo</option>
                                    <option value="10min">10 min</option>
                                    <option value="30min">30 min</option>
                                    <option value="60min">1 hora</option>
                                    <option value="3hr">3 horas</option>
                                    <option value="8hr">8 horas</option>
                                    <option value="14hr">14 horas</option>
                                    <option value="1d">1 día</option>
                                    <option value="2d">2 día</option>
                                    <option value="5d">5 días</option>
                                    <option value="1sem">1 semana</option>
                                    <option value="2sem">2 semanas</option>
                                    <option value="1mes">1 mes</option>
                                    <option value="2meses">2 meses</option>
                                    <option value="infinito">Sin límite de caudicidad</option>
                                  </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="hidden" name="id" value="{{$id}}">
                        <button type="submit" class="btn  btn-primary" style="margin: 0px auto;"> Compartir</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="tools-header">
            @can('clients.delete')
            <div class="tools-menu-btn pull-right lavared-bg white-text">
                <div class="tools-menu-btn-icon"><i class="fas fa-trash-alt"></i></div>
                <a href="{{ route('clients.destroy', ['id' => $client->id_client]) }}"
                    onclick="return confirm('¿Seguro que desea eliminar el cliente{{ $client->client_name }} ?')">
                    Eliminar
                </a>
            </div>
            @endcan
            @can('clients.edit')
            <div class="tools-menu-btn ligthprimary-bg white-text">
                <div class="tools-menu-btn-icon"><i class="fa fa-edit"></i></div>
                <div class="tools-menu-btn-text" data-toggle="modal"><a
                        href="{{ route('clients.edit', ['client' => $client->id_client]) }}">Modificar</a></div>
            </div>
            @endcan

        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-info quot">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="quot-number">
                    <h1>{{ $client->client_name }}</h1>
                </div>
                <div class="estado pull-right">
                    <div class="estado-text">Estado</div>
                    <div class="estado-status">
                        @if ($client->active == 0)
                        <span class="label label-danger">Inactivo</span>
                        @else
                        <span class="label label-success">Activo</span>
                        @endif
                    </div>

                </div>
                <div class="content-divider"></div>
                <div class="container-fixed">
                    <div class="row quot-first-data">
                        <div class="col-xs-12 col-sm-10 no-padding">
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Fecha de creación</div>
                                    <div class="quot-data-box-content"><i class="fa fa-calendar"></i>
                                        {{ date('d-m-Y',strtotime($client->created_at )) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title"> NIT</div>
                                    <div class="quot-data-box-content">{{$client->client_nit}}</div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Tipo de cliente</div>
                                    <div class="quot-data-box-content">{{$client->clientType->type_name}}</div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title"> Código SAP</div>
                                    <div class="quot-data-box-content">{{$client->client_sap_code}}</div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Departamento</div>
                                    <div class="quot-data-box-content">{{$client->department->loc_name}}</div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Ciudad</div>
                                    <div class="quot-data-box-content">{{$client->city->loc_name}}</div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Condicion de pago</div>
                                    <div class="quot-data-box-content">
                                        @if ($client->payterm != "")
                                        {{ $client->payterm->payterm_name }}
                                        @else
                                        -----
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Canal</div>
                                    <div class="quot-data-box-content">{{$client->channel->channel_name}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 no-padding">
                            <div class="quot-data-box gray-box">
                                <div class="quot-data-box-title">Cupo</div>
                                <div class="quot-data-box-precio">
                                    ${{ number_format( $client->client_credit,0, ",", ".") }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <div class="row quot-first-data">
                        <div class="col-xs-12 col-sm-12 no-padding">
                            <div class="col-xs-12 col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Nombre de contacto</div>
                                    <div class="quot-data-box-content">{{$client->client_contact}}</div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Cargo actual</div>
                                    <div class="quot-data-box-content">{{$client->client_position}}</div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Dirección</div>
                                    <div class="quot-data-box-content">{{$client->client_address}}</div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Teléfono</div>
                                    <div class="quot-data-box-content">{{ $client->client_phone }}</div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Email de contacto</div>
                                    <div class="quot-data-box-content">{{$client->client_email}}</div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-title">Email de contacto</div>
                                    <div class="quot-data-box-content">{{$client->client_email}}</div>
                                </div>
                            </div>
                            <div class="col-sm-6 no-padding">
                                <div class="quot-data-box">
                                    <div class="quot-data-box-icon"><i class="ion ion-ios-person"></i></div>
                                    <div class="quot-data-box-title">CAM</div>
                                    <div class="quot-data-box-content-small contact-gray-box">
                                        <div class="email">
                                            Correo: <strong>{{$cam->email}}</strong>
                                        </div>
                                        <div class="info">
                                            Nombre: {{$cam->name}}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-divider"></div>
                    <div class="quot-table-title">
                        <i class="fas fa-folder"></i> Documentos del cliente
                    </div>
                    <div class="row quot-first-data">
                        <div class="col-xs-12 no-padding">
                            <table class="table no-margin table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Documento</th>
                                        <th>Subido el</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($client->files->count())
                                    @foreach($client->files as $key => $doc)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><a href="{{ url('/').'/uploads/'.$client->id_client.'/'.$doc->file_name }}"
                                                target="_blank"><i class="fas fa-file-alt"></i>
                                                {{ $doc->file_name }}</a>
                                        </td>
                                        <td><i class="fa fa-calendar"></i>
                                            {{ date('d-m-Y',strtotime($doc->created_at )) }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td>No hay documentos</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <section class="content">
        <div class="box box-info quot">
            <div class="content-divider"></div>
            <div class="quot-table-title">
                <i class="fa fa-bank"></i> Productos cotizados recientemente
            </div>
            <div class="row quot-first-data">
                <div class="col-xs-12">
                    <table id="cotizaciones" data-toggle="table" data-pagination="true" data-search="true"
                        class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th data-sortable="true">Cotización #</th>
                                <th data-sortable="true">Producto</th>
                                <th data-sortable="true">Cantidad</th>
                                <th data-sortable="true">Precio</th>
                                <th data-sortable="true">Condiciones de pago</th>
                                <th data-sortable="true">Vigencia</th>
                                <th data-sortable="true">Creado el</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($quotations->count())
                            @foreach($quotations as $quotation)
                            <tr>
                                <td><a
                                        href="{{ route('cotizaciones.show',['cotizacione' => $quotation->id_quotation ]) }}">{{ $quotation->quotation->quota_consecutive }}</a>
                                </td>
                                <td>{{ $quotation->product->prod_name }}</td>
                                <td>{{ $quotation->quantity }}</td>
                                <td>${{ number_format( $quotation->totalValue,0, ",", ".") }}</td>
                                <td>
                                    @if ($quotation->payterm->payterm_name != "")
                                    {{ $quotation->payterm->payterm_name }}
                                    @else
                                    -----
                                    @endif
                                </td>
                                <td><i class="fa fa-calendar"></i>
                                    {{ date('d-m-Y',strtotime($quotation->quotation->quota_date_ini )) }}</td>
                                <td><i class="fa fa-calendar"></i>
                                    {{ date('d-m-Y',strtotime($quotation->quotation->quota_date_end )) }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td>No hay productos cotizados</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="content-divider"></div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="box box-info quot">
            <div class="row quot-add-product">
                <div class="col-xs-12">
                    <div class="quot-number">
                        <h3><i class="fa fa-barcode"></i> Productos negociados</h3>
                    </div>
                </div>
                <div class="col-xs-12">
                    <table id="negociaciones" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>NEGOCIACIÓN #</th>
                                <th class="text-center">PRODUCTO</th>
                                <th class="text-center">DESCUENTO</th>
                                <th class="text-center">DESCUENTO ACUMULADO</th>
                                <th class="text-center">CONCEPTO</th>
                                <th class="text-center">ACLARACIÓN</th>
                                <th class="text-center">SUJETO A VOLUMEN</th>
                                <th class="text-center">OBSERVACIONES</th>
                                <th class="text-center">VISIBLE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($negodetails->count())
                            @foreach($negodetails as $nego)
                            <tr @if ($nego->is_valid == 0) class="bg-red" @endif>
                                <td class="text-center"><a
                                        href="{{ route('negociaciones.show',['negociacione' => $nego->id_negotiation ]) }}">{{ $nego->negotiation->negotiation_consecutive}}</a>
                                </td>
                                <td>{{ $nego->product->prod_name }}</td>
                                <td class="text-center">{{ $nego->discount }}%</td>
                                <td class="text-center">{{ $nego->discount_acum }}%</td>
                                @if ($nego->id_concept > 0)
                                <td class="text-center">{{ $nego->concept->name_concept }}</td>
                                @else
                                <td class="text-center">Escala</td>
                                @endif
                                <td class="text-center">{{ $nego->aclaracion }}</td>
                                <td class="text-center upperc">{{ $nego->suj_volumen }}</td>
                                <td class="text-center">{{ $nego->observations}}</td>
                                <td class="text-center">{{ $nego->visible}} </td>
                            </tr>

                            @endforeach
                            @else
                            <tr>
                                <td>No hay registro</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.modal -->
<div class="modal fade " id="modal-cetificate">
    <div class="modal-dialog modal-md">
        <div class="modal-content" class="animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><i class="fa fa-file"></i> Certificación de cliente activo</h3>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="row" id="app">
                        <form action="{{ route('client.pdfcertificate',$client->id_client)}}" method="GET">
                            @csrf
                            <div class="col-xs-12">
                                <div class="datos-title-modal">
                                    El documento está dirigido a:
                                </div>
                            </div>
                            <div class="col-xs-12">
                                {!! Form::hidden('idtype', '5',[]) !!}
                                {!! Form::hidden('type', null,['v-model'=>'sendCot']) !!}
                                <input name="directedto" type="text" class="form-control" id="directedto" required>
                            </div>
                            <div class="col-xs-6">
                                <div class="modal-btn">
                                    {{ Form::button('<i class="fa  fa-file-pdf"></i> Formato simple', ['type' => 'submit', 'class'=> 'btn btn-success pull-center', 'v-on:click'=>'sendCot = false' ]) }}
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="modal-btn">
                                    {{ Form::button('<i class="fa  fa-file-pdf"></i> Formato con cupo', ['type' => 'submit', 'class'=> 'btn btn-success pull-center', 'v-on:click'=>'sendCot = true' ]) }}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.content -->
@endsection
@section('pagescript')
<script src="{{ asset('js/reports.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
@endsection
