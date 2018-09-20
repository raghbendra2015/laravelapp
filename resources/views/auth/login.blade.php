<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Page title -->
    <title>Laravelapp | Login</title>
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->
    <!-- Vendor styles -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.css" />
    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="css/style.css">
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
<div class="login-container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center m-b-md">
               <img class="logo-img" src="<?php echo url('/images/logo.png');?>">
                <!-- <small>Login to the application form here!</small> -->
            </div>
            <div class="hpanel">
                <div class="panel-body">
                    <form   data-parsley-validate="" id="loginForm" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label" for="username">Email<span class="error-mark">*</span></label>
                                <input data-parsley-trigger="change" required="" type="email" placeholder="example@gmail.com" title="Please enter you username" value="" name="username" id="username" class="form-control" data-parsley-required-message="{{config('constant.login_page.EMAIL_REQUIRED')}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="password">Password<span class="error-mark">*</span></label>
                                <input required="" type="password" title="Please enter your password" placeholder="******" value="" name="password" id="password" class="form-control" data-parsley-required-message="{{config('constant.login_page.PASSWORD_REQUIRED')}}" >
                            </div>
                            <button class="btn btn-success btn-block" type="submit">Login</button>
                            <a class="btn btn-success btn-block" href="{{ url('/register') }}">Register</a>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            {{date('Y')}} Copyright Laravel
        </div>
    </div>
</div>
<!-- Vendor scripts -->
<script src="vendor/jquery/dist/jquery.min.js"></script>
<script src="vendor/jquery-ui/jquery-ui.min.js"></script>
<script src="vendor/slimScroll/jquery.slimscroll.min.js"></script>
<script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="vendor/metisMenu/dist/metisMenu.min.js"></script>
<script src="vendor/iCheck/icheck.min.js"></script>
<script src="vendor/sparkline/index.js"></script>
<!-- App scripts -->
<script src="js/homer.js"></script>
<script src="js/toastr.min.js"></script>
<!-- Parsley Scripts -->
<script src="js/parsley.min.js"></script>
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