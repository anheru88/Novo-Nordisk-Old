<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=|, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style type="text/css">
        .table {
            color: #030303;
            font-family: Arial, sans-serif;
            margin: 0px auto;
            width: 700px;
        }

        .headercot {
            background-color: #fff;
            color: #009fda;
            float: left;
            font-size: 20pt;
            font-weight:bold;
            padding: 10px 0px;
            text-transform:uppercase;
            text-align: left;
            width: 90%;
        }

        .description {
            padding: 5px;
            width: 700px;
        }
        .logo {
            text-align: right;
            width: 10%;
        }

        .img {
            float: right;
            width: 100px;
        }
        
        .tg-content {
            font-family: Arial, sans-serif;
            border-color: #e8e8e8 !important;
            text-align: left;
            font-size: 12px;
            vertical-align: top;
            color: #7d7d7d;
            text-align: center;
        }

     

        .footer {
            border-top: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }


        .btn{
            background-color: #009fda;
            border-radius:5px;
            color: white;
            font-size: 12pt;
            text-align: center;
            text-transform: uppercase;
            padding: 5px;
            width: 250px;
        }
        .btn a{
            color: white;
            text-decoration: none
        }
        .creator{
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td>
                <table class="table">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="headercot">Propuesta de cotización </td>
                                    <td class="logo">
                                        <img class="img"
                                            src="https://comercial.nnco.cloud/images/novo_logo_email.png"
                                            alt="Novo Nordisk">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="description">
                            Estimado(a) <br>
                            <strong>{{ $cotizacion->cliente->client_contact }}</strong>   haga click en el siguiente link para descargar su cotización
                        </td>
                    </tr>
                    <tr>
                        <td class="tg-content">
                            <div class="btn">
                                <a href="https://comercial.nnco.cloud/clientquotapdf/{{ $cotizacion->id_quotation }}/pdf/">Cotización {{ $cotizacion->quota_consecutive }}</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Cordialmente, <br><br>
                        <strong class="creator">  {{ $cotizacion->creator->name }}   </strong> <br>
                        Gerente de cuenta <br>
                        Novo Nordisk Colombia SAS
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>