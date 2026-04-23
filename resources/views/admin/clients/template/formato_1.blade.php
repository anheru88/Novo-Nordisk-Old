<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap');

        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin-top: 2cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
            margin-bottom: 2cm;
        }

        .sender-column {
            text-align: right;
        }

        h1 {
            font-size: 1.5em;
        }

        h2 {
            font-size: 1.3em;
        }

        p,
        ul,
        ol,
        dl,
        address {
            font-size: 1.1em;
        }

        p,
        li,
        dd,
        dt,
        address {
            line-height: 1.5;
        }
        .directedTo {
            text-transform: uppercase;
        }
        .headerRight {
            position: relative;
            width: 100%;
        }
        .headerRight img {
            width: 110px;
            height: auto;
        }
        .headerLeft {
            text-align: left;
        }
        .inter_text {
            margin: 0;
            padding: 0;
            text-align: justify;
            text-justify: inter-word;
        }
        .firm {
            display: inline-block;
            margin-top: 100px;
        }
        footer {
            position: absolute;
            top: 100%;
        }
        .footer_left {
            position: absolute;
            font-size: 8pt;
            left: 0%;
        }
        .footer_center {
            position: relative;
            display: flex;
            font-size: 8pt;
            margin: 0 auto;
        }
        .footer_right {
            position: absolute;
            font-size: 8pt;
            right: 0%;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="border-pattern">
        <div class="content">
            <div class="headerRight" align="right">
                <img src="http://comercial.nnco.cloud/images/novo_logo.png" alt="">
            </div>
            <div class="headerLeft" align="left">
                {{$doc_cer->country}},{{$date}}
            </div>

            <div>
                <p>
                    Señores <br> <strong class="directedTo">{{ $directedTo }}</strong> <br> Ciudad
                </p>
            </div>

            <div class="reference">
                <p>
                    Referencia: {{$doc_cer->reference}} {{statusExcelCli($cliente->active)}}
                </p>
            </div>

            <div class="reference">
                <p>
                    {{$doc_cer->header_body}}
                </p>
            </div>

            <div class="inter_text" style="text-align: justify">
                {!! $body !!}
            </div>

            <div class="reference">
                <p>
                    {{$doc_cer->footer_body}}
                </p>
            </div>

            <div class="firm">
                <div class="headerLeft" align="left">
                    <img src="{{ asset('uploads/firms/'. $doc_cer->user_firm) }}" width="320px" alt="">
                </div>
                <p style="text-transform: uppercase">
                    <strong>{!! strtoupper($doc_cer->user_name) !!}</strong> <br> {!! strtoupper($doc_cer->user_position) !!} <br>
                    {{$doc_cer->page_name}}
                </p>
            </div>
        </div>
    </div>

    <footer>
        <div>
            <p class="footer_left" align="left">
                {{$doc_cer->footer_column1_1}} <br> {{$doc_cer->footer_column1_2}} <br> {{$doc_cer->footer_column1_3}}
            </p>

            <p class="footer_center" align="center">
                {{$doc_cer->footer_column2_1}}
            </p>

            <p  class="footer_right" align="right">
                <a href="#">{{$doc_cer->footer_column3_1}}</a>
            </p>
        </div>
    </footer>
</body>

</html>
