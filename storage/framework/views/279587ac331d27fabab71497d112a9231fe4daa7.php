<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Page title -->
        <title>Laravelapp</title>
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <!--<link rel="shortcut icon" type="<?php echo e(url('image/ico')); ?>" href="favicon.ico" />-->

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo e(url('vendor/fontawesome/css/font-awesome.css')); ?>" />
        <link rel="stylesheet" href="<?php echo e(url('vendor/metisMenu/dist/metisMenu.css')); ?>" />
        <link rel="stylesheet" href="<?php echo e(url('vendor/animate.css/animate.css')); ?>" />
        <link rel="stylesheet" href="<?php echo e(url('vendor/bootstrap/dist/css/bootstrap.css')); ?>" />

        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo e(url('fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css')); ?>" />
        <link rel="stylesheet" href="<?php echo e(url('fonts/pe-icon-7-stroke/css/helper.css')); ?>" />
        <link rel="stylesheet" href="<?php echo e(url('css/style.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(url('css/toastr.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(url('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" />

    </head>
    <style type="text/css">
        .icon-off:before{content:"\f00d"; font-family: FontAwesome; font-size:16px; font-style: normal}
    </style>
    <body class="fixed-navbar fixed-sidebar">

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

        <!-- Header -->
        <div id="header">
            <div class="color-line">
            </div>
            <div id="logo" class="light-version">
                <span>
                    <img src="<?php echo url('/images/logo.png');?>" width="100">
                </span>
            </div>
            <nav role="navigation">
                <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
                <div class="small-logo">
                    <span class="text-primary"> <img src="<?php echo url('/images/logo.png');?>" width="100"></span>
                </div>
                <div class="mobile-menu">
                    <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="collapse mobile-navbar" id="mobile-collapse">
                        <ul class="nav navbar-nav">
                            <li>
                                <a class="" href="#">Profile</a>
                            </li>
                            <li>
                                <a class="" href="#">Change Password</a>
                            </li>
                            <li>
                                <a class="" href="<?php echo e(url('/login')); ?>">Logout</a>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="navbar-right">
                    <ul class="nav navbar-nav no-borders">
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="pe-7s-user"></i> <span class="small"><?php echo e(Auth::user()->first_name); ?> <?php echo e(Auth::user()->last_name); ?> </span> <i class="pe-7s-angle-down"></i>
                            </a>

                            <ul class="dropdown-menu hdropdown notification animated flipInX">
                                <li class="summary">
                                    <a href="<?php echo e(url('/logout')); ?>"> 
                                        Logout
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                        
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Navigation -->
        <aside id="menu">
            <div id="navigation">
                

                <ul class="nav" id="side-menu">

                    <li>
                        <a href="<?php echo e(url('/dashboard')); ?>" class="<?php echo e(Request::segment(1) == 'dashboard' ? 'active' : ''); ?>"> <span class="nav-label">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/films')); ?>" class="<?php echo e(Request::segment(1) == 'film' ? 'active' : ''); ?>"><span class="nav-label">films</span></a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div id="wrapper">

            <?php echo $__env->yieldContent('content'); ?>

            <!-- Footer-->
            <footer class="footer">
                <?php echo e(date('Y')); ?> Copyright Laravelapp
            </footer>
        </div>
        <!-- Vendor scripts -->
        <script src="<?php echo e(url('vendor/jquery/dist/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(url('vendor/jquery-ui/jquery-ui.min.js')); ?>"></script>
        <script src="<?php echo e(url('vendor/slimScroll/jquery.slimscroll.min.js')); ?>"></script>
        <script src="<?php echo e(url('vendor/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
        <script src="<?php echo e(url('vendor/metisMenu/dist/metisMenu.min.js')); ?>"></script>
        <script src="<?php echo e(url('vendor/iCheck/icheck.min.js')); ?>"></script>
        <script src="<?php echo e(url('vendor/sparkline/index.js')); ?>"></script>
        <script src="<?php echo e(url('js/toastr.min.js')); ?>"></script>
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
        <?php echo app('toastr')->render(); ?>
        <!-- DataTables -->
        <script src="<?php echo e(url('vendor/datatables/media/js/jquery.dataTables.min.js')); ?>"></script>
        <script src="<?php echo e(url('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js')); ?>"></script>
        <!-- DataTables buttons scripts -->
        <script src="<?php echo e(url('vendor/datatables.net-buttons/js/buttons.html5.min.js')); ?>"></script>
        <script src="<?php echo e(url('vendor/datatables.net-buttons/js/buttons.print.min.js')); ?>"></script>
        <script src="<?php echo e(url('vendor/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
        <script src="<?php echo e(url('vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')); ?>"></script>
        <!-- App scripts -->
        <script src="<?php echo e(url('js/homer.js')); ?>"></script>
        <script src="<?php echo e(url('js/parsley.min.js')); ?>"></script>
       
        <?php echo $__env->yieldContent('javascript'); ?>
    </body>
</html>