<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>S&L | Admin Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta charset="UTF-8">
    <meta name="description" content="Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="stacks" />

    <!-- Styles -->
    {!! HTML::style('admin/plugins/pace-master/themes/blue/pace-theme-flash.css') !!}
    {!! HTML::style('admin/plugins/uniform/css/default.css') !!}
    {!! HTML::style('admin/plugins/bootstrap/css/bootstrap.min.css') !!}
    {!! HTML::style('admin/plugins/fontawesome/css/font-awesome.min.css') !!}
    {!! HTML::style('admin/plugins/line-icons/simple-line-icons.css') !!}
    {!! HTML::style('admin/plugins/offcanvasmenueffects/css/menu_cornerbox.css') !!}
    {!! HTML::style('admin/plugins/waves/waves.min.css') !!}
    {!! HTML::style('admin/plugins/switchery/switchery.min.css') !!}
    {!! HTML::style('admin/plugins/3d-bold-navigation/css/style.css') !!}
    {!! HTML::style('admin/plugins/slidepushmenus/css/component.css') !!}
    {!! HTML::style('admin/plugins/weather-icons-master/css/weather-icons.min.css') !!}
    {!! HTML::style('admin/plugins/toastr/toastr.min.css') !!}
    {!! HTML::style('admin/plugins/bootstrap-switch/css/bootstrap-switch.min.css') !!}
    {!! HTML::style('admin/plugins/dropzone/dropzone.min.css') !!}

    <!-- Theme Styles -->
    {!! HTML::style('admin/css/l4m.min.css') !!}
    <link href="{{ url('admin/css/layers/dark-layer.css') }}" class="theme-color" rel="stylesheet" type="text/css"/>
    {!! HTML::style('admin/css/custom.css') !!}

    {!! HTML::script('admin/plugins/3d-bold-navigation/js/modernizr.js') !!}
    @if(false)
    {!! HTML::script('admin/plugins/jquery/jquery-3.1.0.min.js') !!}
    @else
    {!! HTML::script('admin/plugins/jquery/jquery-2.2.4.min.js') !!}
    @endif

    @yield('header')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="compact-menu hover-menu">
<div class="overlay"></div>
<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s1">
    <h3><span class="pull-left">Messages</span><a href="javascript:void(0);" class="pull-right" id="closeRight"><i class="icon-close"></i></a></h3>
    <div class="slimscroll">
        <a href="javascript:void(0);" class="showRight2">@if(false)<img src="assets/images/avatar2.png" alt="">@endif<span>Michael Lewis<small>Nice to meet you</small></span></a>
        <a href="javascript:void(0);" class="showRight2">@if(false)<img src="assets/images/avatar3.png" alt="">@endif<span>John Doe<small>Nice to meet you</small></span></a>
        <a href="javascript:void(0);" class="showRight2">@if(false)<img src="assets/images/avatar4.png" alt="">@endif<span>Emma Green<small>Nice to meet you</small></span></a>
        <a href="javascript:void(0);" class="showRight2">@if(false)<img src="assets/images/avatar5.png" alt="">@endif<span>Nick Doe<small>Nice to meet you</small></span></a>
        <a href="javascript:void(0);" class="showRight2">@if(false)<img src="assets/images/avatar2.png" alt="">@endif<span>Michael Lewis<small>Nice to meet you</small></span></a>
        <a href="javascript:void(0);" class="showRight2">@if(false)<img src="assets/images/avatar3.png" alt="">@endif<span>John Doe<small>Nice to meet you</small></span></a>
        <a href="javascript:void(0);" class="showRight2">@if(false)<img src="assets/images/avatar4.png" alt="">@endif<span>Emma Green<small>Nice to meet you</small></span></a>
        <a href="javascript:void(0);" class="showRight2">@if(false)<img src="assets/images/avatar5.png" alt="">@endif<span>Nick Doe<small>Nice to meet you</small></span></a>
    </div>
</nav>
<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
    <h3><span class="pull-left">Michael Lewis</span> <a href="javascript:void(0);" class="pull-right" id="closeRight2"><i class="fa fa-angle-right"></i></a></h3>
    <div class="slimscroll chat">
        <div class="chat-item chat-item-left">
            <div class="chat-image">
                @if(false)<img src="assets/images/avatar2.png" alt="">@endif
            </div>
            <div class="chat-message">
                Duis aute irure dolor?
            </div>
        </div>
        <div class="chat-item chat-item-right">
            <div class="chat-message">
                Lorem ipsum dolor sit amet, dapibus quis, laoreet et.
            </div>
        </div>
        <div class="chat-item chat-item-left">
            <div class="chat-image">
                @if(false)<img src="assets/images/avatar2.png" alt="">@endif
            </div>
            <div class="chat-message">
                Ut ullamcorper, ligula.
            </div>
        </div>
        <div class="chat-item chat-item-right">
            <div class="chat-message">
                In hac habitasse platea dict umst. ligula eu tempor eu id tincidunt.
            </div>
        </div>
        <div class="chat-item chat-item-left">
            <div class="chat-image">
                @if(false)<img src="assets/images/avatar2.png" alt="">@endif
            </div>
            <div class="chat-message">
                Curabitur pretium?
            </div>
        </div>
        <div class="chat-item chat-item-right">
            <div class="chat-message">
                Etiam tempor. Ut tempor! ull amcorper.
            </div>
        </div>
    </div>
    <div class="chat-write">
        <form class="form-horizontal" action="javascript:void(0);">
            <input type="text" class="form-control" placeholder="Say something">
        </form>
    </div>
</nav>
<form class="search-form" action="#" method="GET">
    <div class="input-group">
        <input type="text" name="search" class="form-control search-input" placeholder="Type something...">
                <span class="input-group-btn">
                    <button class="btn btn-default close-search" type="button"><i class="icon-close"></i></button>
                </span>
    </div><!-- Input Group -->
</form><!-- Search Form -->
<main class="page-content content-wrap">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="sidebar-pusher">
                <a href="javascript:void(0);" class="waves-effect waves-button push-sidebar">
                    <i class="icon-arrow-right"></i>
                </a>
            </div>
            <div class="logo-box">
                <a href="{{ url('home') }}" class="logo-text"><span>S-L Shop</span></a>
                @if(false)<img src="http://polydec.muskimagazin.rs/polydec/img/Logo_PolyDec-v2.png" alt="" style="width: 80px; margin-top: 10px; margin-left: 5px;">@endif

            </div><!-- Logo Box -->
            <div class="search-button">
                <a href="javascript:void(0);" class="show-search"><i class="icon-magnifier"></i></a>
            </div>
            <div class="topmenu-outer">
                @include('admin.partials.top-menu')
            </div>
        </div>
    </div><!-- Navbar -->
    <div class="page-sidebar sidebar">
        <div class="page-sidebar-inner slimscroll">
            @include('admin.partials.left-menu')
        </div><!-- Page Sidebar Inner -->
    </div><!-- Page Sidebar -->
    <div class="page-inner">
        <div class="page-title">
            <div class="page-breadcrumb">
                @yield('bredcrumb')
            </div>
        </div>
        <div id="main-wrapper">
            @yield('content')
        </div><!-- Main Wrapper -->
        <div class="page-footer">
            <p class="no-s">Developerd by Mini Studio Publishing Group</p>
        </div>
    </div><!-- Page Inner -->
</main><!-- Page Content -->
<nav class="cd-nav-container" id="cd-nav">
    <header>
        <h3>DEMOS</h3>
    </header>
    <div class="col-md-6 demo-block demo-selected demo-active">
        <p>Dark<br>Design</p>
    </div>
    <div class="col-md-6 demo-block">
        <a href="http://steelcoders.com/meteor/admin2/index.html"><p>Light<br>Design</p></a>
    </div>
    <div class="col-md-6 demo-block">
        <a href="http://steelcoders.com/meteor/admin3/index.html"><p>Material<br>Design</p></a>
    </div>
    <div class="col-md-6 demo-block demo-coming-soon">
        <p>Horizontal<br>(Coming)</p>
    </div>
    <div class="col-md-6 demo-block demo-coming-soon">
        <p>Coming<br>Soon</p>
    </div>
    <div class="col-md-6 demo-block demo-coming-soon">
        <p>Coming<br>Soon</p>
    </div>
</nav>
<div class="cd-overlay"></div>


<!-- Javascripts -->


{!! HTML::script('admin/plugins/jquery-ui/jquery-ui.min.js') !!}
{!! HTML::script('admin/plugins/bootstrap/js/bootstrap.min.js') !!}
{!! HTML::script('admin/plugins/pace-master/pace.min.js') !!}
{!! HTML::script('admin/plugins/switchery/switchery.min.js') !!}
{!! HTML::script('admin/plugins/uniform/js/jquery.uniform.standalone.js') !!}

{!! HTML::script('admin/plugins/3d-bold-navigation/js/main.js') !!}

{!! HTML::script('admin/plugins/waves/waves.min.js') !!}
{!! HTML::script('admin/plugins/jquery-blockui/jquery.blockui.js') !!}
{!! HTML::script('admin/plugins/jquery-slimscroll/jquery.slimscroll.min.js') !!}
{!! HTML::script('admin/plugins/offcanvasmenueffects/js/classie.js') !!}
{!! HTML::script('admin/plugins/toastr/toastr.min.js') !!}
{!! HTML::script('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') !!}
{!! HTML::script('admin/plugins/dropzone/dropzone.min.js') !!}
@if(false)



{!! HTML::script('admin/plugins/waypoints/jquery.waypoints.min.js') !!}

{!! HTML::script('admin/plugins/flot/jquery.flot.min.js') !!}
{!! HTML::script('admin/plugins/flot/jquery.flot.time.min.js') !!}
{!! HTML::script('admin/plugins/flot/jquery.flot.symbol.min.js') !!}
{!! HTML::script('admin/plugins/flot/jquery.flot.resize.min.js') !!}
{!! HTML::script('admin/plugins/flot/jquery.flot.tooltip.min.js') !!}
{!! HTML::script('admin/plugins/curvedlines/curvedLines.js') !!}
{!! HTML::script('admin/plugins/chartjs/Chart.bundle.min.js') !!}
{!! HTML::script('admin/js/pages/dashboard.js') !!}


@endif
{!! HTML::script('admin/js/l4m.min.js') !!}


@yield('footer')

<script>
    $(function(){
        @yield('footer_scripts')
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

</body>

</html>
