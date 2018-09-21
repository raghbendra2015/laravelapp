<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Page title -->
    <title>Laravelapp</title>
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->
    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{ url('vendor/fontawesome/css/font-awesome.css') }}" />
    <link rel="stylesheet" href="{{ url('vendor/metisMenu/dist/metisMenu.css') }}" />
    <link rel="stylesheet" href="{{ url('vendor/animate.css/animate.css') }}" />
    <link rel="stylesheet" href="{{ url('vendor/bootstrap/dist/css/bootstrap.css') }}" />
    <!-- App styles -->
    <link rel="stylesheet" href="{{ url('fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}" />
    <link rel="stylesheet" href="{{ url('fonts/pe-icon-7-stroke/css/helper.css') }}" />
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

</head>
<style type="text/css">
  .icon-off:before{content:"\f00d"; font-family: FontAwesome; font-size:16px; font-style: normal}
</style>
<body class="blank">

<!-- Simple splash screen-->
<div class="splash">
            <div class="color-line"></div>
            <div class="splash-title">
                <h1>Laravelapp</h1>
                <div class="spinner">
                    <div class="rect1"></div>
                    <div class="rect2"></div> 
                    <div class="rect3"></div> 
                    <div class="rect4"></div> 
                    <div class="rect5"></div>
                </div> 
            </div>
        </div>
<!--[if lt IE 7]>
<p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="color-line"></div>
<div class="front-container">
    @if(!Auth::check())
        <div style="float:right;">
        <a href="{{ url('/login') }}" class="btn btn-success" title="Login">LOGIN</a>
                                                <a href="{{ url('/register') }}" class="btn btn-success" title="Registration">REGISTRATION</a>
                                            </div>
    @else
        <div style="float:right;">
                <a href="{{ url('/logout') }}" class="btn btn-success" title="Logout"> 
                                        Logout
                                    </a>
        </div>                            
    @endif
    <br>
    @yield('content')
    <div class="row">
        <div class="col-md-12 text-center">
            {{date('Y')}} Copyright Laravel
        </div>
    </div>
</div>
<!-- Vendor scripts -->
<script src="{{ url('vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ url('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ url('vendor/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ url('vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ url('vendor/metisMenu/dist/metisMenu.min.js') }}"></script>
<script src="{{ url('vendor/iCheck/icheck.min.js') }}"></script>
<script src="{{ url('vendor/sparkline/index.js') }}"></script>
<!-- App scripts -->
<script src="{{ url('js/homer.js') }}"></script>
<script src="{{ url('js/toastr.min.js') }}"></script>
<!-- Parsley Scripts -->
<script src="{{ url('js/parsley.min.js') }}"></script>
<script>
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-bottom-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "2000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};
</script>
@toastr_render
</body>
</html>