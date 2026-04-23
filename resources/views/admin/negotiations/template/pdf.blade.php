<html>

<head>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap');

        /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
        @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            font-family: 'Roboto', sans-serif;
            font-size: 1.2em;
            margin-top: 2.5cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
            margin-bottom: 2cm;
            text-align: justify;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 1cm;
            left: 1cm;
            right: 1cm;
            height: 3cm;
            margin-top: -15px
        }

        main {
            position: relative;
            padding-top: 40px;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 1cm;
            left: 1cm;
            right: 0cm;
            height: 1cm;
        }

        table {
            page-break-inside: auto;
        }

        .body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 10pt;
        }


        .tg {
            border-collapse: collapse;
            border-spacing: 0;
            width: 400px;
        }

        .tg td {
            font-size: 9pt;
            padding: 5px 5px;
            border-style: solid;
            border-width: 1px;
            overflow: hidden;
            word-break: normal;
            border-color: #ccc;
        }

        .tg th {
            font-size: 9pt;
            font-weight: normal;
            padding: 5px 5px;
            border-style: solid;
            border-width: 1px;
            overflow: hidden;
            word-break: normal;
            border-color: #ccc;
        }

        .tg .tg-ped4 {
            background-color: #004f79;
            border-color: #003f79;
            color: white;
            font-weight: bold;
            text-align: center;
            vertical-align: middle
        }

        .tg .tg-0pky {
            border-color: #ddd;
            vertical-align: top
        }

        .bg-gray {
            background-color: #f7f7f7
        }

        .top-align {
            vertical-align: top
        }

        .row {
            width: 100%;
        }

        .logo {
            float: right;
            margin-left: 15px;
            margin-right: 15px;
            width: 80px;
        }

        .logo img {
            height: auto;
            width: 100%;
        }

        .table {
            width: 100%
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .cotizacion {
            font-size: 11pt;
            font-weight: bold;
            width: 60%;
        }

        .logoTable {
            height: 60px;
        }

        .date {
            margin-bottom: 30px;
        }

        .docdata {
            margin-bottom: 30px;
        }

        .text-desc {
            margin: 20px 0px;
        }

        .sub-title {
            font-size: 10pt;
            margin: 20px 0px 10px;
            width: 100%;
        }

        .prod-name {
            font-weight: bold;
            margin: 20px 0px 10px;
            width: 100%;
        }


        .title {
            font-weight: bold;
            font-size: 1.2em;
            text-transform: uppercase
        }

        .title2 {
            font-weight: bold;
            font-size: 1.1em;
        }

        .terminos {
            margin: 50px auto 0px;
            text-align: justify;
            width: 100%;
        }


        .terminos-title {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .terminos-content {
            font-size: 8pt;
        }

        .contacto {
            text-transform: capitalize
        }

        .upper {
            text-transform: uppercase
        }

        .break {
            page-break-after: always;
        }

        .cond-oferta {
            margin-top: 30px;
        }

        .sign-data {
            display: block;
            left: 0%;
            margin-top: 10px;
            position: relative;
            top: 25%;
            width: 100%;
        }

        .img-responsive img {
            width: 100%;
            height: auto;
        }

        .img-sign {
            border-bottom: 2px solid #000;
            float: left;
            margin: 10px 0px 0px 0px;
            padding: 0px;
            width: 180px;
        }

        .img-sign img {
            width: 100%;
        }

        .name-sign {
            text-align: left;
            text-transform: uppercase;
            width: 100%;
        }

        .charge-sign {
            width: 100%;
            display: inline-table;
        }

        .signs {
            margin-bottom: 30px;
            width: 100%;
        }

        .condiciones {
            margin: 5px 0;
            font-weight: bold
        }

        .margin-top {
            margin-top: -45px;
        }


        .novo-data {
            color: #666;
            font-family: 'Roboto', sans-serif;
            font-size: .7em;
            line-height: 1.1em;
            padding: 5px 5px 5px;
        }

        .cot-number {
            font-size: 1.8em;
            font-weight: bold;
            text-transform: uppercase
        }

        .cot-data {
            border-left: solid #009fda 3px;
            padding-left: 15px
        }

        .valides {
            background-color: #f5f5f5;
            border-radius: 5px;
            font-size: .9em;
            margin-left: -8px;
            padding: 8px;
            width: 90%
        }

        .badge {
            background-color: #dd4b39;
            border-radius: 5px;
            color: #fff;
            padding: 5px
        }

        ol li {
            margin-bottom: 10px
        }

        .capital {
            text-transform: capitalize;
        }

        .footer-span {
            float: left;
            line-height: 5pt;
            padding: 0;
            margin: 0;
            width: 33%;
        }
        .sign-container{
            padding-right: 20px;
            width: 100%;
        }
        .sign {
            padding-bottom: 50px;
            padding-right: 50px;
            width: 40%;
        }

        .sign-data {
            display: block;
            margin-top: 10px;
            position: relative;
            width: 100%;
        }

        .img-responsive img {
            width: 100%;
            height: auto;
        }

        .img-sign {
            border-bottom: 2px solid #000;
            float: left;
            margin: 10px 0px 0px 0px;
            padding: 0px;
            padding-bottom: 10px;
            width: 100%;
        }

        .img-sign img {
            height: auto;
            width: 100%;
        }

        .name-sign {
            font-size: 1em;
            margin-top: 10px;
            width: 100%;
        }

        .charge-sign {
            width: 100%;
            display: inline-table;
        }

        .padd{
            padding: 10px 10px 0px
        }

    </style>

</head>

<body class="body">
    <script type="text/php">
        if (isset($pdf)) {
                    $text = "Página {PAGE_NUM} / {PAGE_COUNT}";
                    $size = 9;
                    $font = $fontMetrics->getFont("Verdana");
                    $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                    $x = $pdf->get_width() - 100;
                    $y = $pdf->get_height() - 35;
                    $pdf->page_text($x, $y, $text, $font, $size);
                }
            </script>
    <header>
        <table class="table">
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="text-right top-align">
                    <div class="logo"><img src="http://comercial.nnco.cloud/images/novo_logo.png" alt=""></div>

                </td>
            </tr>
        </table>
    </header>

    <footer>
        <div class="novo-data">
            <div class=""><span class="footer-span"> Novo Nordisk Colombia S.A.S.</span> <span
                    class="footer-span">+57 1 314 9999 PBX</span> <span class="footer-span"><a
                        href="www.novonordisk.com.co">www.novonordisk.com.co</a></span></div>
            <div class="address">Calle 125 No. 19-24 P. 6 </div>
            <div class="city">Bogotá D.C. - Colombia</div>
        </div>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <div class="cabecera">
            <div class="date">Bogotá D.C., {{ $desde }} </div>
            <div class="docdata">
                Señores <br>
                <strong>{{ $negociacion->cliente->client_name }}</strong> <br>
                {{ $negociacion->cliente->client_contact }}<br>
                {{ $negociacion->cliente->client_position }}<br>
                Bogotá
            </div>
            <div class="doc-intro">
                <strong> Ref: Propuesta comercial año <?php echo date('Y'); ?> - Novo Nordisk Colombia SAS</strong>
                <br><br>
                Respetados Señores,<br><br>
                Nos permitimos presentar la propuesta comercial para la vigencia comprendida entre el
                <strong>{{ $desde }} hasta el {{ $hasta }}</strong>, como se describe a continuación:
            </div>
        </div>
        {{ $escalas }}
        @if ($escalas == true)
            <div class="text-desc">
                <div class="title"><strong>Descuentos por escalas </strong> </div> <br>
                Se otorgarán descuentos vía Nota Crédito (NC), de acuerdo con el volumen de ventas de cada mes, de
                acuerdo con las siguientes escalas:
            </div>
            @php
                $numeral = 0;
            @endphp
            @foreach ($lines as $key => $line)
                @php
                    $count = $key + 1;
                @endphp

                @php
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
                                } else {
                                    $package_unit = ${'idProds' . $line->id_line}[$counter][0]->prod_package_unit;
                                    $id_measure_unit = ${'idProds' . $line->id_line}[$counter][0]->id_measure_unit;
                                }
                                $counter = $counter + 1;
                            @endphp
                            <div class="prod-name capital">
                                {{ strtolower($products[0]->brand_name) }}
                            </div>
                            @foreach ($products as $key => $prod)
                                <div class="productos">
                                    <table class="tg">
                                        @if ($key == 0)
                                            <thead>
                                                <tr>
                                                    <th class="tg-ped4" style="width:50%">Rango Volumen <span
                                                            class="capital">
                                                            {{ strtolower(measureUnit($id_measure_unit)) }}</span>
                                                    </th>
                                                    <th class="tg-ped4" style="width:50%">% Descuento Comercial
                                                    </th>
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
                <div class="title"><strong>Descuentos comerciales no sujetos a volumen </strong></div> <br>
                Se otorgarán descuentos vía Nota Crédito (NC) sobre el valor de la venta de cada mes, en los
                porcentajes relacionados a continuación.
                <ol>
                    @foreach ($products_no_vol as $prod)
                        <li>{{ $prod->product->prod_name }} - {{ $prod->discount }}%</li>
                    @endforeach
                </ol>
            </div>
        @endif
        @if ($products_info->count())
            <div class="text-desc">
                <div class="title"><strong>Descuento - Información de consumos </strong></div><br>
                Se otorgará un 1% sobre el valor de la venta mensual vía nota crédito (NC), por concepto de
                información de consumos.
                Los datos de rotación mensual deben discriminarse por marca/presentación y deben contener la
                siguiente información: <br>
                <ul>
                    <li>Canal</li>
                    <li>Convenio</li>
                    <li>Ciudad</li>
                    <li>Departamento</li>
                    <li>Punto de entrega</li>
                    <li>Cantidad</li>
                </ul>
                <strong> NOTA:</strong> Este descuento aplica únicamente para las ventas de todas las presentaciones
                comerciales de los siguientes productos:
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
        @if ($aditionalData != '')
            <div class="text-desc">
                <div class="title">OTRAS NEGOCIACIONES</div>
                {!! $aditionalData !!}
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

        <div class="text-desc">
            <div class="title">Condiciones generales</div> <br>
            Nos permitimos aclarar las siguientes consideraciones que forman parte integral de esta propuesta:
            <br>

            <ol>
                <li>Condición de pago: <strong>{{ $negociacion->cliente->payterm->payterm_name }}</strong></li>
                <li>La vigencia de la propuesta comercial: <strong>{{ $desde }} hasta
                        {{ $hasta }}</strong></li>
                <li>Las cotizaciónes que soporta la presente propuesta comercial son
                    @foreach ($quota_consecutive as $quota)
                        <strong> No. {{ $quota->quota_consecutive }} de
                            {{ date('d-m-Y', strtotime($quota->quota_date_ini)) }}, </strong>
                    @endforeach
                </li>
                <li>
                    El descuento comercial se hará efectivo mediante la generación de Notas Crédito al final de
                    cada mes, de acuerdo con el descuento comercial
                    aplicable según la escala de cada producto y/o condición comercial negociada. Las Notas
                    Crédito tendrán validez únicamente dentro de los 8
                    meses siguientes a la generación de la misma; una vez finalizado este período Novo Nordisk
                    Colombia SAS se reserva el derecho de aplicación
                    del descuento de acuerdo con la cartera vigente.
                </li>
                <li>
                    Esta propuesta contiene productos que se encuentran actualmente en control de precios por
                    las circulares emitidas por el gobierno nacional.
                    Por esta razón, cualquier cambio de precios ordenado en estas circulares o resoluciones,
                    disminución o aumentos de precios, modificarán los precios
                    informados en la cotización mencionada de manera inmediata y se comunicarán los ajustes a
                    las condiciones comerciales negociadas.
                </li>
                <li>
                    Como parte integral de la presente propuesta comercial, se adjunta la política devoluciones
                    de Novo Nordisk Colombia S.A.S.
                </li>
                <li>
                    En virtud de la presente propuesta comercial, el destinatario al cual se remite la presente
                    propuesta se obliga a no revelar, divulgar, exhibir,
                    mostrar, comunicar, utilizar y/o emplear la información del presente documento; con persona
                    natural o jurídica, en su favor o en el de terceros, y en
                    consecuencia a mantenerla de manera confidencial y privada y a proteger dicha información
                    para evitar su divulgación no autorizada, ejerciendo sobre
                    ésta el mismo grado de diligencia que utiliza para proteger información confidencial de su
                    propiedad. La información sólo podrá ser utilizada para
                    el propósito expresado en el encabezado de este documento. Adicionalmente sólo podrá
                    reproducirse dicha información confidencial si ello resulta
                    necesario para cumplir tal finalidad y sólo podrá darse a conocer a aquellos empleados,
                    trabajadores o asesores que tengan necesidad de conocerla
                    para la mencionada finalidad. En caso de que se les entregue información confidencial a
                    dichos empleados, trabajadores o asesores, se les debe
                    advertir su carácter confidencial.
                </li>
            </ol>

        </div>
        <div class="signs">
            Cordialmente,
        </div>
        <table>
            <tbody>
                @if (sizeof($approving) > 0)
                <tr>
                @foreach ($approving as $key => $approver)
                    <td class="padd">
                        <img src="{{ asset('/uploads/firms/' . $approver->approversUser->firm) }}" alt="" width="300px">
                        <hr>
                        <div class="name-sign">
                            <strong>
                            {!! strtoupper($approver->approversUser->name) !!} <br>
                            {!! strtoupper($approver->approversUser->position) !!} <br>
                            </strong>
                            Novo Nordisk Colombia SAS
                        </div>
                    </td>
                @if ($key % 2 == 0 && $key > 0)
                </tr>
                <tr>
                    <br>
                @endif
                @endforeach
                </tr>
                <tr style="margin-top: 20px">
                    <td class="padd">
                        <br>
                        <img src="{{ asset('/uploads/firms/' . $negociacion->creator->firm) }}" alt="" width="300px">
                        <hr>
                        <div class="name-sign">
                            <strong>
                            {!! strtoupper($negociacion->creator->name) !!} <br>
                            {!! strtoupper($negociacion->creator->position) !!} <br>
                            </strong>
                            Novo Nordisk Colombia SAS
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </main>
</body>

</html>
