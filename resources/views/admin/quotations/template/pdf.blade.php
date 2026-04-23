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
            margin-top: 4cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
            margin-bottom: 2cm;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 1cm;
            left: 1cm;
            right: 1cm;
            height: 3.5cm;
            margin-top: -15px;
        }

        main {
            position: relative;
            padding-top: 40px;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 0cm;
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
            width: 100%;
        }

        .tg td {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            padding: 5px 5px;
            border-style: solid;
            border-width: 1px;
            overflow: hidden;
            word-break: normal;
            border-color: #ccc;
        }

        .tg th {
            font-family: Arial, sans-serif;
            font-size: 8pt;
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
            vertical-align: middle;
        }

        .tg .tg-0pky {
            border-color: #ddd;
            vertical-align: top;
        }

        .bg-gray {
            background-color: #f7f7f7;
        }

        .top-align {
            vertical-align: top;
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
            width: 100%;
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

        .title {
            font-weight: bold;
            font-size: 1.3em;
        }

        .title2 {
            font-weight: bold;
            font-size: 1.1em;
        }

        .terminos {
            margin: 0px auto 0px;
            padding: 0px;
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
            text-transform: capitalize;
        }

        .upper {
            text-transform: uppercase;
        }

        .break {
            page-break-after: always;
        }

        .cond-oferta {
            margin-top: 0px;
        }

        .sign-container{
            padding-right: 20px;
            width: 100%;
        }

        .sign {
            padding-bottom: 50px;
            padding-right: 50px;
            width: 25%;
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
            display: inline-table;
            font-weight: bold;
            width: 100%;
        }

        .charge-sign {
            width: 100%;
            display: inline-table;
        }

        .condiciones {
            margin: 5px 0;
            font-weight: bold;
        }

        .margin-top {
            margin-left: -15px;
            margin-top: -45px;
        }


        .novo-data {
            color: #333;
            font-family: 'Roboto', sans-serif;
            font-size: .7em;
            line-height: 1.1em;
            padding: 5px 5px 5px;
        }

        .cot-number {
            font-size: 1.8em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .cot-data {
            border-left: solid #009fda 3px;
            padding-left: 15px;
        }

        .valides {
            background-color: #f5f5f5;
            border-radius: 5px;
            font-size: .9em;
            margin-left: -8px;
            padding: 8px;
            width: 90%;
        }

        /* .justify {
            text-align: justify;
            text-justify: inter-word;
        } */

        .badge {
            background-color: #dd4b39;
            border-radius: 5px;
            color: #fff;
            padding: 5px
        }

        p {
            margin: 0;
            padding: 0;
            text-align: justify;
            text-justify: inter-word;
        }
    </style>

    <title>Cotización {{ $cotizacion->quota_consecutive }}</title>
</head>

<body class="body">
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} / {PAGE_COUNT}";
            $size = 10;
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
                <td>
                    <div class="cot-data">
                        <div class="cot-number"> Cotización {{ $cotizacion->quota_consecutive }} </div>
                        <div class="title2 upper">{{ $cotizacion->cliente->client_name }} - Nit:
                            {{ $cotizacion->cliente->client_nit }}</div>
                        <div class="contacto">{{ $cotizacion->cliente->client_contact }} - <span
                                class="upper">{{ $cotizacion->cliente->client_position }}</span></div>
                        <div class="city">{{ $cotizacion->cliente->city->loc_name }}</div>
                        <div class="valides">
                            <i class="far fa-calendar-alt"></i> Vigente desde: <strong>{{ $desde }}</strong> - Vigente
                            hasta: <strong> {{ $hasta }} </strong>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                </td>
                <td class="text-right top-align">
                    <div class="logo">
                        <img src="http://comercial.nnco.cloud/images/novo_logo.png" alt="">
                    </div>
                    <div class="novo-data">
                        <div class="title">Novo Nordisk Colombia SAS</div>
                        <div class="nit">Nit No. 900.557875-3</div>
                        <div class="address">Calle 125 No. 19-24 P. 6 | Tel No. (57) 1 314 9999</div>
                        <div class="city">Bogotá D.C., Colombia</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>

                </td>
                <td class="text-center">

                </td>
                <td class="logoTable">

                </td>
            </tr>
        </table>

    </header>

    <footer>
        Copyright &copy; <?php echo date("Y");?>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <div class="productos break">
            <table class="tg margin-top">
                <?php for ($i=0; $i < sizeof($productos) ; $i++) { ?>
                @if ($i == 0)
                <thead>
                    <tr>
                        <th class="tg-ped4" style="width:13%">REGISTRO SANITARIO</th>
                        <th class="tg-ped4" style="width:15%">NOMBRE COMERCIAL</th>
                        <th class="tg-ped4" style="width:22%">PRESENTACIÓN COMERCIAL</th>
                        <th class="tg-ped4" style="width:10%">CANTIDAD COTIZADA</th>
                        <th class="tg-ped4" style="width:10%">VALOR UNIDAD MÍNIMA</th>
                        <th class="tg-ped4" style="width:10%">VALOR PRESENTACIÓN COMERCIAL</th>
                        <th class="tg-ped4" style="width:10%">VALOR TOTAL COTIZADO</th>
                        <th class="tg-ped4" style="width:10%">CONDICIONES FINANCIERAS</th>
                    </tr>
                </thead>
                @endif
                <tbody>
                    <tr>
                        <td class="tg-0pky text-center bg-gray">{{ $productos[$i]->product->prod_invima_reg }} </td>
                        <td class="tg-0pky text-center">{{ $productos[$i]->product->prod_name  }} </td>
                        <td class="tg-0pky text-center">{{ $productos[$i]->product->prod_package }} </td>
                        <td class="tg-0pky text-center">{{ $productos[$i]->quantity }}</td>
                        <td class="tg-0pky text-center bg-gray">
                            ${{ number_format(($productos[$i]->totalValue / $productos[$i]->quantity), 0, ",", ".") }}
                        </td>
                        <td class="tg-0pky text-center">${{ number_format($productos[$i]->totalValue, 0, ",", ".") }}
                        </td>
                        <td class="tg-0pky text-center bg-gray">
                            ${{ number_format($productos[$i]->totalValue, 0, ",", ".") }}</td>
                        @if ($productos[$i]->is_valid == 0)
                        <td class="tg-0pky text-center">
                            {{ $productos[$i]->payterm->payterm_name }} <br> <span class="badge"> Sin vigencia </span>
                        </td>
                        @else
                        <td class="tg-0pky text-center">
                            {{ $productos[$i]->payterm->payterm_name }}
                        </td>
                        @endif
                    </tr>
                </tbody>
                <?php } ?>
            </table>
        </div>
        {{-- <div class="break"></div> --}}
        <div class="cond-oferta break">
            <div class="condiciones">CONDICIONES DE LA OFERTA</div>
                <section class="">
                    <div>
                        <h5>Plazo de Entrega:</h5>
                        <p>{!! $doc->conditions_time !!}</p>
                    </div>
                    <div>
                        <h5>Observaciones:</h5>
                        <p class="justify">{!! $doc->conditions_content !!}</p>
                    </div>
                    <div>
                        <h5>Válidez de la oferta:</h5>
                        <p>{{ date('d/m/Y', strtotime($cotizacion->quota_date_end)) }}</p>
                    </div>
                    <div>
                        <h5>Condición especial:</h5>
                        <p>{!! $doc->conditions_special !!}</p>
                    </div>
                    <div>
                        <h5>Contacto:</h5>
                        <p>Representante Comercial: <strong> {{ ucwords(strtolower($cotizacion->creator->name)) }}
                            </strong> Celular: {{ $cotizacion->creator->phone }} Dirección Comercial: Calle 125 No.
                            19-24 P6 Bogotá D.C.</p>
                    </div>
                </section>
                <hr>
                <table class="sign-container">
                    @if (sizeof($approving) > 0)
                    <tr>
                        @foreach ($approving as $key => $approver)
                        <td class="sign">
                            <div class="img-sign">
                                <img src="{{  asset('/uploads/firms/'.$approver->approversUser->firm) }}" alt="">
                            </div>
                            <div class="sign-data">
                                <div class="name-sign">
                                    {!! $approver->approversUser->name !!}
                                </div>
                                <div class="charge-sign">
                                    {!! $approver->approversUser->position !!}
                                </div>
                                <div class="position">
                                    Novo Nordisk Colombia SAS
                                </div>
                            </div>
                        </td>
                        @if ($key % 2 == 0 && $key > 0)
                        </tr>
                        <tr>
                        @endif
                        @endforeach
                        <td class="sign">
                            <div class="img-sign">
                                <img src="{{  asset('/uploads/firms/'.$cotizacion->cliente->users->firm) }}" alt="">
                            </div>
                            <div class="sign-data">
                                <div class="name-sign">
                                    {!! strtoupper($cotizacion->cliente->users->name) !!}
                                </div>
                                <div class="charge-sign">
                                    {!! strtoupper($cotizacion->cliente->users->position) !!}
                                </div>
                                <div class="position">
                                    Novo Nordisk Colombia SAS
                                </div>
                            </div>
                        </td>
                    </tr>
                    @else
                    <tr>
                    <td class="sign">
                        <div class="img-sign">
                            <img src="{{  asset('/uploads/firms/'.$cotizacion->cliente->users->firm) }}" alt="">
                        </div>
                        <div class="sign-data">
                            <div class="name-sign">
                                {!! strtoupper($cotizacion->cliente->users->name) !!}
                            </div>
                            <div class="charge-sign">
                                {!! strtoupper($cotizacion->cliente->users->position) !!}
                            </div>
                            <div class="position">
                                Novo Nordisk Colombia SAS
                            </div>
                        </div>
                    </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        {{-- <div class="break"></div> --}}
        <div class="cond-comerciales">
            <div class="terminos">
                <div class="terminos-title text-center">
                    CONDICIONES COMERCIALES PORTAFOLIO NOVO NORDISK COLOMBIA S.A.S.
                    Nit No. 900.557.875-3
                </div>
                <div class="terminos-content">
                    {!! $doc->footer !!}
                </div>
            </div>
        </div>
    </main>
</body>

</html>
