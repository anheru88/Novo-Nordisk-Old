<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CAMTool - Novo Nordisk</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.ico')}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href={{ asset('adminlte/bootstrap/dist/css/bootstrap.min.css') }}>
    <!-- Font Awesome -->
    <link rel="stylesheet" href={{ asset('adminlte/font-awesome/css/font-awesome.min.css') }}>
    <!-- Ionicons -->
    <link rel="stylesheet" href={{ asset('adminlte/Ionicons/css/ionicons.min.css') }}>
    <!-- DataTables -->
    <link rel="stylesheet"
        href={{ asset('adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet"
        href={{ asset('adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}>
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
        href={{ asset('adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}>
    <link rel="stylesheet" href={{ asset('adminlte/bower_components/select2/dist/css/select2.min.css') }}>
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/AdminLTE.min.css') }}">

    <link rel="stylesheet" href={{ asset('adminlte/css/skins/skin-blue.min.css') }}>

    <link rel="stylesheet" href={{ asset('css/fileinput.css') }}>
    <link rel="stylesheet" href={{ asset('css/dropzone.css') }}>
    <link rel="stylesheet" href={{ asset('css/semantic.min.css') }}>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css"
        integrity="sha512-oe8OpYjBaDWPt2VmSFR+qYOdnTjeV9QPLJUeqZyprDEQvQLJ9C5PCFclxwNuvb/GQgQngdCXzKSFltuHD3eCxA=="
        crossorigin="anonymous" />
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/amsify.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    {{-- bootstrap-tagsinput.min --}}
    <link href="{{ asset('css/bootstrap-tagsinput.css') }}" rel="stylesheet">
    @toastr_css
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="preloader-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <div class="wrapper">
        @include('admin.layouts.statusbar')
        <!-- Left side column. contains the logo and sidebar -->
        @include('admin.layouts.mainmenu')
        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        @include('admin.layouts.footer')
    </div>
</body>

<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 3 -->
<script src="{{ asset('adminlte/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('adminlte/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

<script src="{{ asset('adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('adminlte/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- Uploader Docs -->
<script src="{{ asset('js/fileinput.js') }}"></script>

{{-- bootstrap-tagsinput.min --}}
<script src="{{ asset('js/bootstrap-tagsinput.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/locale/bootstrap-table-es-MX.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.26.0/tableExport.min.js"></script>
<script>
    $(function() {
        $('#table').bootstrapTable()
    })
</script>
<script src={{ asset('js/datepicker.es-ES.js') }}></script>
<script src="{{ asset('js/amsify.js') }}"></script>
<script src="{{ asset('js/generic.js') }}"></script>

<script src="{{ asset('js/app_components.js') }}"></script>
<script src="{{ asset('js/semantic.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    $(document).ready(function() {
        /* $('#datatable1').DataTable({
            'language': {
                "url": "lang/es/datatable.es.lang",
            },
            'aaSorting': []
        }); */

        jQuery('.clientes-select').select2({
            placeholder: "Seleccione",
    allowClear: true
        });
        jQuery('.productos-select').select2();
    });
</script>
@include('sweetalert::alert', ['cdn' =>
"https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.all.js"])
@toastr_js
@toastr_render
@yield('pagescript')

</html>
