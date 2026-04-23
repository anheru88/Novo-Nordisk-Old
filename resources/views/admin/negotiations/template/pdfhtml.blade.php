@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <form action="" method="get">
        @include('admin.layouts.breadcrumbs')
        <div class="negotiation" id="app">
            <section class="content-header">
                <div class="bread-crumb">
                    <a href="{{ route('home') }}">Inicio</a> / Negociaciones
                </div>
                <h1>
                    Previsualización Negociación
                </h1>
            </section>
            <section class="content">
                <div class="box box-info quot">
                    <div class="box-body">
                    <main class="neg-preview">
                        <div class="cabecera">
                            <div class="date">Bogotá D.C., {{ $desde }} </div>
                            <div class="docdata">
                                Señores <br><br>
                                <div class="client-name">{{ $negociacion->cliente->client_name }}</div>
                                <div class="client-contact"> {{ $negociacion->cliente->client_contact }}</div>
                                <div class="client-position">{{ $negociacion->cliente->client_position }}</div>
                                <div class="city">Bogotá</div>
                            </div>
                            <div class="doc-intro">
                                <strong> Ref: Propuesta comercial año
                                    <?php echo date('Y'); ?> - Novo Nordisk Colombia  SAS
                                </strong>
                                <br><br>
                                Respetados Señores,<br><br>
                                Nos permitimos presentar la propuesta comercial para la vigencia comprendida entre el
                                <strong>{{ $desde }} hasta el {{ $hasta }}</strong>, como se describe a continuación:
                            </div>
                        </div>
                        @if ($escalas == true)
                        <div class="text-desc">
                            <div class="title">Descuentos por escalas </div>
                            <div class="description"> Se otorgarán descuentos vía Nota Crédito (NC), de acuerdo con el volumen de ventas de cada mes, de acuerdo con las siguientes escalas:</div>
                        </div>
                        @php $numeral = 0; @endphp
                        @foreach ($lines as $key => $line)
                        @php
                            $count = $key + 1;
                            $counter = 0;
                            $numeral = $numeral + 1;
                        @endphp
                        @if (${'prods' . $line->id_line})
                        <div class="sub-title">
                            <strong> 1.{{ $numeral }} Portafolio {{ $line->line_name }} </strong>
                        </div>
                        @foreach (${'prods' . $line->id_line} as $key => $products)
                            @if (sizeof(${'prods' . $line->id_line}) <= 0)
                            @else
                                @php
                                if (sizeof(${'idProds' . $line->id_line}) <= 0) {
                                    $package_unit = 1;
                                    $id_measure_unit = '';
                                }else {
                                    $package_unit = ${'idProds'. $line->id_line}[$counter][0]->prod_package_unit;
                                    $id_measure_unit = ${'idProds' . $line->id_line}[$counter][0]->id_measure_unit;
                                }
                                $counter = $counter + 1;
                            @endphp
                                <div class="prod-name capital">
                                    {{ $products[0]->brand_name }}
                                </div>
                                @foreach ($products as $key => $prod)
                                <div class="productos">
                                    <table class="tg">
                                        @if ($key == 0)
                                        <thead>
                                            <tr>
                                                <th class="tg-ped4" style="width:50%">Rango Volumen
                                                    <span class="capital">
                                                        {{ strtoupper(measureUnit($id_measure_unit)) }}
                                                    </span>
                                                </th>
                                                <th class="tg-ped4" style="width:50%">% Descuento Comercial </th>
                                            </tr>
                                        </thead>
                                        @endif
                                        <tbody>
                                            <tr>
                                                @php
                                                if ($prod->observations != 'N/A' && $prod->observations != '') {
                                                $datos = preg_split('/ /', $prod->observations);
                                                if (intval($datos[0]) > 1) {
                                                $val1 = round(intval($datos[0]) / $package_unit);
                                                } else {
                                                $val1 = $datos[0];
                                                }
                                                if ($datos[2] != 'MÁS') {
                                                $val2 = round(intval($datos[2]) / $package_unit);
                                                } else {
                                                $val2 = $datos[2];
                                                }
                                                $rangeVol = $val1 . ' ' . $datos[1] . ' ' . $val2;
                                                } else {
                                                $rangeVol = $prod->observations;
                                                }
                                                @endphp
                                                <td class="tg-0pky text-center bg-gray" style="width:50%">
                                                    {{ $rangeVol }}</td>
                                                <td class="tg-0pky text-center" style="width:50%">
                                                    {{ $prod->discount }}%</td>
                                            </tr>
                                        <tbody>
                                    </table>
                                </div>
                                @endforeach
                                @endif
                                <br>
                                @endforeach
                                @endif
                                @endforeach
                                @endif
                                @if ($products_no_vol->count())
                                <div class="text-desc">
                                    <div class="title"><strong>Descuentos comerciales no sujetos a volumen</strong></div>
                                    <div>
                                        Se otorgarán descuentos vía Nota Crédito (NC) sobre el valor de la venta de cada mes, en
                                        los porcentajes relacionados a continuación.
                                        <ol>
                                            @foreach ($products_no_vol as $prod)
                                            <li>{{ $prod->product->prod_name }} - {{ $prod->discount }}%</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                @endif
                                @if ($products_info->count())
                                <div class="text-desc">
                                    <div class="title"><strong>Descuento - Información de consumos </strong></div>
                                    <br>
                                    Se otorgará un 1% sobre el valor de la venta mensual vía nota crédito (NC), por
                                    concepto de información de consumos.
                                    Los datos de rotación mensual deben discriminarse por marca/presentación y deben
                                    contener la siguiente información: <br>
                                    <ul>
                                        <li>Canal</li>
                                        <li>Convenio</li>
                                        <li>Ciudad</li>
                                        <li>Departamento</li>
                                        <li>Punto de entrega</li>
                                        <li>Cantidad</li>
                                    </ul>
                                    <strong> NOTA:</strong> Este descuento aplica únicamente para las ventas de todas
                                    las presentaciones comerciales de los siguientes productos:
                                    <div class="text-desc">
                                        <ol>
                                            @foreach ($products_info as $prod)
                                            <li>{{ $prod->product->prod_name }} - {{ $prod->discount }}%</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                    <div class="note">Esta información debe ser enviada a más tardar el día 15 de cada mes.</div>
                                </div>
                                @endif
                                @if ($nego_especiales->count())
                                <div class="text-desc">
                                    <div class="title">Negociaciones especiales</div>
                                </div>
                                <div class="text-desc">
                                    <ol>
                                        @foreach ($nego_especiales as $prod)
                                        <li>{{ $prod->product->prod_name }} - {{ $prod->discount }}%</li>
                                        @endforeach
                                    </ol>
                                </div>
                                @endif

                                @if ($packx3->count())
                                <div class="text-desc">
                                    <div class="title">Descuentos por presentación</div>
                                </div>
                                <div class="text-desc">
                                    <ol>
                                        @foreach ($packx3 as $prod)
                                        <li>{{ $prod->product->prod_name }} - {{ $prod->discount }}%</li>
                                        @endforeach
                                    </ol>
                                </div>
                                @endif

                                <hr>
                                <div class="text-desc">
                                    <div class="title"> Negociaciones Especiales</div>
                                    <div> Describir características específicas de la negociación. (si aplica)</div>
                                    {!! Form::hidden('id', $negociacion->id_negotiation) !!}
                                    <input type="hidden" name="state" value="true">
                                    <textarea name="aditionalData" id="aditionalData"
                                        class="form-control"></textarea><br>
                                </div>
                                <hr>

                                <div class="text-desc">
                                    <div class="title">Condiciones generales</div>
                                    <div class="condiciones">
                                        Nos permitimos aclarar las siguientes consideraciones que forman parte integral de esta propuesta: <br><br>
                                        <ol>
                                            <li>Condición de pago:
                                                <strong>{{ $negociacion->cliente->payterm->payterm_name }}</strong>
                                            </li>
                                            <li>La vigencia de la propuesta comercial: <strong>{{ $desde }} hasta
                                                    {{ $hasta }}</strong></li>
                                            <li>Las cotizaciónes que soporta la presente propuesta comercial son
                                                @foreach ($quota_consecutive as $quota)
                                                <strong> No. {{ $quota->quota_consecutive }} de
                                                    {{ date('d-m-Y', strtotime($quota->quota_date_ini)) }}, </strong>
                                                @endforeach
                                            </li>
                                            <li>
                                                El descuento comercial se hará efectivo mediante la generación de Notas
                                                Crédito al final de cada mes, de acuerdo con el descuento comercial
                                                aplicable según la escala de cada producto y/o condición comercial negociada. Las
                                                Notas Crédito tendrán validez únicamente dentro de los 8 meses siguientes a la generación de la misma; una vez finalizado este
                                                período Novo Nordisk Colombia SAS se reserva el derecho de aplicación del descuento de acuerdo con la cartera vigente.
                                            </li>
                                            <li>
                                                Esta propuesta contiene productos que se encuentran actualmente en control
                                                de precios por las circulares emitidas por el gobierno nacional.
                                                Por esta razón, cualquier cambio de precios ordenado en estas circulares o
                                                resoluciones, disminución o aumentos de precios, modificarán los precios
                                                informados en la cotización mencionada de manera inmediata y se comunicarán
                                                los ajustes a las condiciones comerciales negociadas.
                                            </li>
                                            <li>
                                                Como parte integral de la presente propuesta comercial, se adjunta la
                                                política devoluciones de Novo Nordisk Colombia S.A.S.
                                            </li>
                                            <li>
                                                En virtud de la presente propuesta comercial, el destinatario al cual se
                                                remite la presente propuesta se obliga a no revelar, divulgar, exhibir,
                                                mostrar, comunicar, utilizar y/o emplear la información del presente
                                                documento; con persona natural o jurídica, en su favor o en el de terceros, y en
                                                consecuencia a mantenerla de manera confidencial y privada y a proteger
                                                dicha información para evitar su divulgación no autorizada, ejerciendo sobre
                                                ésta el mismo grado de diligencia que utiliza para proteger información
                                                confidencial de su propiedad. La información sólo podrá ser utilizada para
                                                el propósito expresado en el encabezado de este documento. Adicionalmente
                                                sólo podrá reproducirse dicha información confidencial si ello resulta
                                                necesario para cumplir tal finalidad y sólo podrá darse a conocer a aquellos
                                                empleados, trabajadores o asesores que tengan necesidad de conocerla
                                                para la mencionada finalidad. En caso de que se les entregue información
                                                confidencial a dichos empleados, trabajadores o asesores, se les debe
                                                advertir su carácter confidencial.
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="signs">
                                    Cordialmente,
                                </div>
                                <div class="sign-data">
                                    <div class="sign">
                                        <table>
                                            <tr>
                                                @foreach ($approving as $authSign)
                                                <td>
                                                    <div class="pic-sign img-responsive">
                                                        <img src="{{ asset('/uploads/firms/'.$authSign->approversUser->firm) }}" alt="" width="100" height="100">
                                                    </div>
                                                    <div class="name-sign">
                                                        {!! $authSign->approversUser->name !!}
                                                    </div>
                                                    <div class="charge-sign">
                                                        {!! $authSign->approversUser->position !!} <br>
                                                        Novo Nordisk Colombia SAS
                                                    </div>
                                                </td>
                                                @endforeach
                                                <td>
                                                    <div class="pic-sign img-responsive">
                                                        <img src="{{ asset('/uploads/firms/'.$negociacion->creator->firm) }}" alt="" width="100" height="100">
                                                    </div>
                                                    <div class="name-sign">
                                                        {!! $negociacion->creator->name !!}
                                                    </div>
                                                    <div class="charge-sign">
                                                        {!! $negociacion->creator->position !!} <br>
                                                        Novo Nordisk Colombia SAS
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </main>
                        <input type="submit" value="Descargar PDF" class="btn btn-success btn-pdf">
                    </div>
                </div>
            </section>
        </div>
    </form>
</div>
<!-- /.content -->
@endsection
@section('pagescript')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('aditionalData', {
            language: 'es',
            width: 1000,
            filebrowserUploadUrl: "{{ route('upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form',
            stylesSet: 'my_styles'
        });
    CKEDITOR.stylesSet.add( 'my_styles', [
        { name: 'Novo tabla', element: 'table', styles: { 'border': '1px solid #ddd',  'border-collapse': 'collapse', 'min-width': '150px' } },
        { name: 'Título tabla', element: 'td', styles: { 'background-color': '#004f79', 'color': 'white', 'padding': '0 30px', 'font-weight': 'bold','border': '1px solid #333'} },
        { name: 'Fondo gris', element: 'td', styles: { 'background-color': '#d2d6de', 'color': 'black', 'padding': '0 10px', 'text-align': 'center'} },
        { name: 'Tabla generica', element: 'td', styles: { 'text-align': 'center' } },
    ]);
</script>
@endsection
