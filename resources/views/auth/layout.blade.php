<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CAMTool - Novo Nordisk</title>

    <link rel="shortcut icon" href="{{{ asset('images/favicon.ico') }}}">


    <!-- Styles -->
    <link rel="stylesheet" href="adminlte/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="adminlte/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="adminlte/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="adminlte/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
      page. However, you can choose any other skin. Make sure you
      apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="adminlte/css/skins/skin-blue.min.css">

    <link rel="stylesheet" href="adminlte/plugins/iCheck/square/blue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/fontawesome.min.css" integrity="sha512-8Vtie9oRR62i7vkmVUISvuwOeipGv8Jd+Sur/ORKDD5JiLgTGeBSkI3ISOhc730VGvA5VVQPwKIKlmi+zMZ71w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body >
    <header class="header" role="header">
        <!-- START c58-ie8-disclaimer-top component-->
        <!--[if lte IE 8]>
            <div id="ie8-disclaimer-top" class="ie8-disclaimer-top">
                <div class="ie8-disclaimer-body">
                    <p>Your browser is out of date and may not be compatible with our website.</p><a id="" href="#" title="" rel="" role="" target="" class="ie8-close-disclaimer-body"><img src="/etc/designs/nextweb/csslibs/images/icons/modal-close-dlpx.png" alt="" title="" role="presentation" class="img img-responsive js-image-responsive"/></a>
                </div>
            </div>
        <![endif]-->
        <!-- END c57-ie8-disclaimer component-->
        <div class="container">
            <div class="row">
                <div class="col-xs-3 col-sm-2 col-md-2">
                    <div class="header-logo">
                        <img src="{{asset('images/novo-logo-68.png')}}" alt="Novo Nordisk">
                    </div>
                </div>
                <div class="hidden-xs col-sm-7 col-md-8"></div>
                <div class="col-xs-9 col-sm-3 col-md-2">
                    <div class="header-app-login">
                        Commercial Agility Management Tool
                    </div>
                </div>
            </div>
        </div>
        </div>
    </header>
    <div id="app">
        @yield('content')
    </div>
    <footer role="footer" class="footer">
        <div class="footertoolbar">
            <div class="footer-bottom">
                <div class="container">
                    <a href="" target="">®2019 Novo Nordisk A/S</a>
                    <a href="" target="">Disclaimer/Privacy</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- jQuery 3 -->
<script src="adminlte/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="adminlte/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="adminlte/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>

</html>
