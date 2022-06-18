<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="utf-8" />
    <title>CENTINEL PARKING MANAGEMENT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css"  id="app-stylesheet" />
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style type="text/css">
            @import url('https://rsms.me/inter/inter.css');
        *{
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }

        h1, h2, h3, h4, h5, h6, input, p, button, i, div, span, a, .btn{
          font-family: 'Inter', sans-serif;
          text-shadow: 0px 0px 1px #000;
        }

        input, p, button, i, div, span, a, .btn{
            font-size: 12px;
        }

        th{
            font-weight: bold;
        }

        .carder{
            background-color: white; 
            height:70px; width: 79%; 
            margin-top:10px; 
            margin-left:20%; 
            box-shadow:3px 3px 10px #c4c4c4, -7px -7px 10px white;
        }

        .card{
            box-shadow:3px 3px 10px #c4c4c4, -10px -10px 10px white;
        }

        .left-active{
            background-color: rgba(255, 255, 255, 1); 
            background-image: linear-gradient(rgba(196, 196, 196, 0.8), rgba(196, 196, 196, 0.4)); 
            box-shadow:inset 3px 3px 10px rgba(0, 0, 0, 0.6), inset -10px -10px 10px white; 
            border-top-right-radius: 50px; 
            border-bottom-right-radius: 50px;
        }

        .btn{
            box-shadow:1px 1px 5px rgba(0, 0, 0, 0.6), -1px -1px 3px white;
        }

        .logout{
            font-weight: 500;
            text-shadow: 0 0 3px black;
            box-shadow: inset 1px 1px 7px black, inset -1px -1px 5px white;
        }
    </style>

</head>
<body>
    <!-- Begin page -->
    <div id="wrapper">

            
        <!-- Topbar Start -->
        <div class="navbar-custom" style="background-color: #f8fafc;">

            <!-- LOGO -->
            <div class="logo-box" style="background-color: #373a3c;">
                <a href="index.html" class="logo text-center logo-dark">
                    <span class="logo-lg">
                        <h4 class="mt-4" style="color:white; font-weight:bold; text-shadow:0px 0px 2px white;">CENTINEL SITE</h4>
                        <!-- <span class="logo-lg-text-dark">Uplon</span> -->
                    </span>
                    <span class="logo-sm">
                        <!-- <span class="logo-lg-text-dark">U</span> -->
                        <img src="assets/images/logo-sm-light.png" alt="" height="24">
                    </span>
                </a>
            </div>

            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <button class="button-menu-mobile waves-effect waves-light">
                        <i class="mdi mdi-menu" style="font-size:20px;"></i>
                    </button>
                </li>
            </ul>
        </div>
        <!-- end Topbar -->

        
        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu" style="background-color: #373a3c;">

            <div class="slimscroll-menu">

                <!--- Sidemenu -->
                <div id="sidebar-menu">

                    <ul class="metismenu" id="side-menu">

                    </ul>

                </div>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- END wrapper -->

    @isset($_GET['error'])
        @if($_GET['error'])
            <!--  Modal content for error notification -->
            <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" id="myModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title" id="myCenterModalLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body text-center">
                            <h6 class="">{{$_GET['error']}}</h6>
                            <button class="btn btn-info mt-4" data-dismiss="modal" aria-hidden="true">Done</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        @endif
    @endisset

    @isset($_GET['success'])
        @if($_GET['success'])
            <!--  Modal content for success notification -->
            <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" id="myModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title" id="myCenterModalLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body text-center">
                            <h6 class="">{{$_GET['success']}}</h6>
                            <button class="btn btn-info mt-4" data-dismiss="modal" aria-hidden="true">Done</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        @endif
    @endisset
    @yield('footer')
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#myModal').modal('show');
        });
    </script>
</body>
</html>
