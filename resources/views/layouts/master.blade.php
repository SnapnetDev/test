<!DOCTYPE html>
<html class="no-js css-menubar" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@if (!request()->filled('excel'))

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HCMatrix') }}</title>
        <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">
        <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
        <!-- Stylesheets -->
        <link rel="stylesheet" href="{{ asset('global/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
        <link rel="stylesheet" href="{{ asset('global/css/bootstrap-extend.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
        <!-- Plugins -->
        <link rel="stylesheet" href="{{ asset('global/vendor/animsition/animsition.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/asscrollable/asScrollable.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/switchery/switchery.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/intro-js/introjs.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/slidepanel/slidePanel.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/flag-icon-css/flag-icon.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/waves/waves.css') }}">

        <link rel="stylesheet" href="{{ asset('global/vendor/toastr/toastr.css') }}">
        @yield('stylesheets')

        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('global/fonts/font-awesome/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('global/fonts/material-design/material-design.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/fonts/brand-icons/brand-icons.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/notie/notie.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/summernote/summernote.min.css') }}">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
        <!--[if lt IE 9]>
    <script src="{{ asset('global/vendor/html5shiv/html5shiv.min.js') }}"></script>
    <![endif]-->
        <!--[if lt IE 10]>
    <script src="{{ asset('global/vendor/media-match/media.match.min.js') }}"></script>
    <script src="{{ asset('global/vendor/respond/respond.min.js') }}"></script>


    <![endif]-->
        <!-- Scripts -->
        <style type="text/css">
            .site-menu-scroll-wrap.is-list {
                width: 300px;
            }

            .site-menu-title {
                max-width: 300px;
            }

            .hidden {
                display: none;
            }

            .loader_load-container {
                height: Auto;
                width: 90%;
                font-family: Helvetica;
            }

            .loader_load {
                height: 200px;
                width: 700px;
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                margin: auto;
                font-size: 48px;
                text-align: center;
            }

            .progress-dot {
                animation-name: progressr;
                animation-timing-function: ease-in-out;
                animation-duration: 3s;
                animation-iteration-count: infinite;
                height: 40px;
                width: 40px;
                border-radius: 100%;
                background-color: black;
                position: absolute;
                border: 2px solid white;
            }

            .loader_load--dot {
                animation-name: loader_load;
                animation-timing-function: ease-in-out;
                animation-duration: 3s;
                animation-iteration-count: infinite;
                height: 40px;
                width: 40px;
                border-radius: 100%;
                background-color: black;
                position: absolute;
                border: 2px solid white;
            }

            #hcmlogo {
                animation-name: logo;
                animation-timing-function: ease-in-out;
                animation-duration: 1s;
                animation-iteration-count: infinite;
                animation-delay: 0s;
            }

            .progress-dot:first-child {
                background-color: #8cc759;
                animation-delay: 1.5s;
            }

            .progress-dot:nth-child(2) {
                background-color: #8c6daf;
                animation-delay: 1.45s;
            }

            .progress-dot:nth-child(3) {
                background-color: #ef5d74;
                animation-delay: 1.4s;
            }

            .progress-dot:nth-child(4) {
                background-color: #f9a74b;
                animation-delay: 1.35s;
            }

            .progress-dot:nth-child(5) {
                background-color: #60beeb;
                animation-delay: 1.3s;
            }

            .progress-dot:nth-child(6) {
                background-color: #fbef5a;
                animation-delay: 1.25s;
            }

            .progress-dot:nth-child(7) {
                background-color: #ef5d74;
                animation-delay: 1.2s;
            }

            .progress-dot:nth-child(8) {
                background-color: #f9a74b;
                animation-delay: 1.15s;
            }

            .progress-dot:nth-child(9) {
                background-color: #60beeb;
                animation-delay: 1.10s;
            }

            .progress-dot:nth-child(10) {
                background-color: #fbef5a;
                animation-delay: 1.05s;
            }

            .loader_load--text {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                width: 100%;
                margin: auto;
            }

            .loader_load--text:after {
                content: "Please wait while we setup the system for you";
                font-weight: bold;
                animation-name: loading-text;
                animation-duration: 3s;
                animation-iteration-count: infinite;
            }

            @keyframes progressr {
                15% {
                    transform: translateX(0);
                }

                45% {
                    transform: translateX(650px);
                }

                65% {
                    transform: translateX(650px);
                }

                95% {
                    transform: translateX(0);
                }
            }

            @keyframes loading-text {
                0% {
                    content: "Please wait while we setup the system for you";
                }

                25% {
                    content: "Please wait while we setup the system for you.";
                }

                50% {
                    content: "Please wait while we setup the system for you..";
                }

                75% {
                    content: "Please wait while we setup the system for you...";
                }
            }

            @keyframes logo {
                15% {
                    transform: translateY(-50px);

                }

                35% {

                    transform: translateY(-100px);
                }

                65% {
                    transform: translateY(0px);

                }

                95% {
                    transform: translateY(0px);
                }
            }

        </style>
        <style type="text/css">
            .bigdrop.select2-container .select2-results {
                min-height: 200px;
            }

            .bigdrop .select2-results {
                min-height: 200px;
            }

            .bigdrop .select2-choices {
                min-height: 150px;
                min-height: 150px;
                overflow-y: auto;
            }

            #site-navbar-search .select2-selection {
                height: 60px;
                border: 0px solid white;
            }

            #site-navbar-search .select2-selection__rendered {
                padding: 12px 20px 10px 50px;
            }

            #site-navbar-search .select2-selection__arrow {
                top: 17px !important;
            }

            .select2-container--open {
                z-index: 9999999 !important;
            }

            .alertify {
                z-index: 999999;
            }

            .datepicker-dropdown {
                z-index: 2147483647 !important;
            }

            .pointer {
                cursor: pointer;
            }

            /*    .btn-info {
                color: #fff;
                border-color: #



            {{ companyInfo()->color }}



            ;
                                            background-color: #



            {{ companyInfo()->color }}



            ;
                                        }*/
            .modal-info .modal-header {
                border-radius: .286rem .286rem 0 0;
                background-color: #{{ companyInfo()->color }};
            }

            .bg-light-blue-500,
            .bg-cyan-600 {
                background-color: # {{ companyInfo()->color }} !important;
            }

            .panel-info>.panel-heading {
                color: #fff;
                background-color: #{{ companyInfo()->color }};
                border-color: #{{ companyInfo()->color }};
            }

            .panel-line.panel-info .panel-heading {
                color: # {{ companyInfo()->color }} !important;
                background: transparent;
                border-top-color: # {{ companyInfo()->color }} !important;
            }

            .panel-line.panel-info .panel-title {
                color: # {{ companyInfo()->color }} !important;
            }

            .wc-header {
                background-color: #008B8B !important;
                box-shadow: 0 1px rgba(0, 0, 0, 0.2);
                box-sizing: content-box;
                color: #ffffff;
                font-weight: 500;
                height: 30px;
                left: 0;
                letter-spacing: 0.5px;
                padding: 8px 8px 0 8px;
                position: absolute;
                right: 0;
                top: 0;
                z-index: 1;
            }

            .chat-div {
                font-size: 16px;
                transform: translateZ(0px);
                display: inline;
                z-index: 100003;
                position: fixed;
                height: 27.00em;
                width: 20.00em;
                top: 72%;
                margin-top: -16.375em;
                left: 91.5%;
                margin-left: -12.625em;
                background-color: #fff;
                border-radius: 8px;
                border: thin dotted #ddd;
                opacity: 0.9;
            }

            #chatShow {
                /*margin-top: 23%; */
                bottom: 0px;
                right: 0px;
                z-index: 2000;
            }

            #chatHide {
                /*margin-top: -10%; */
                bottom: 0px;
                right: 0px;
                position: fixed;
                z-index: 2000;
            }

            .page-aside {
                width: 215px;
            }


            .page-aside-left .page-aside+.page-main {
                margin-left: 221px;
            }

            #loader {
                position: absolute;
                left: 50%;
                top: 50%;
                z-index: 1;
                width: 150px;
                height: 150px;
                margin: -75px 0 0 -75px;
                border: 16px solid #f3f3f3;
                border-radius: 50%;
                border-top: 16px solid #3498db;
                width: 120px;
                height: 120px;
                -webkit-animation: spin 2s linear infinite;
                animation: spin 2s linear infinite;
            }

            /* Safari */
            @-webkit-keyframes spin {
                0% {
                    -webkit-transform: rotate(0deg);
                }

                100% {
                    -webkit-transform: rotate(360deg);
                }
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }

            }

            .lsetf_logo{
            max-width: 130px;
            height: auto;
            margin-left: 20px;
            
            }

        </style>
        <script src="{{ asset('global/vendor/breakpoints/breakpoints.js') }}"></script>
        <script>
            Breakpoints();

            function setfy() {

                var year = document.getElementById('fiscalyear').value;
                $.get('{{ url('setfy') }}/' + year, function(data, status, xhr) {

                    if (xhr.status == 200) {


                        window.location.reload();

                    }
                });


            }

            function setcpny() {

                var company_id = document.getElementById('cpny').value;
                $.get('{{ url('setcpny') }}/' + company_id, function(data, status, xhr) {

                    if (xhr.status == 200) {

                        console.log(data);
                        window.location = '{{ url('home') }}';

                    }
                });


            }

        </script>

    </head>
@endif
{{-- animsition site-navbar-small app-{{isset($pageType) ? $pageType : 'contacts'}} page-aside-left --}}

<body class="animsition site-navbar-small app-{{ isset($pageType) ? $pageType : 'contacts' }} page-aside-left">


    <div id="loader" style="display: none;z-index:9999999;"></div>
    @if (!request()->filled('excel'))
        <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->
        <nav class="site-navbar navbar navbar-inverse bg-light-blue-500 navbar-fixed-top navbar-mega" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
                    data-toggle="menubar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="hamburger-bar"></span>
                </button>
                <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
                    data-toggle="collapse">
                    <i class="icon md-more" aria-hidden="true"></i>
                </button>
                <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
                    <a href="{{ route('home') }}" style="color: #fff;text-decoration: none">
                        <img alt="" class="navbar-brand-logo" src="{{ asset('assets/images/logo.png') }}">

                        <span class="navbar-brand-text hidden-xs-down">{{ systemInfo()['name'] }}</span>
                    </a>
                </div>
                <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search"
                    data-toggle="collapse">
                    <span class="sr-only">Toggle Search</span>
                    <i class="icon md-search" aria-hidden="true"></i>
                </button>
            </div>
            <div class="navbar-container container-fluid">
                <!-- Navbar Collapse -->
                <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
                    <!-- Navbar Toolbar -->
                    <ul class="nav navbar-toolbar">
                        <li class="nav-item hidden-float" id="toggleMenubar">
                            <a class="nav-link" data-toggle="menubar" href="#" role="button">
                                <i class="icon hamburger hamburger-arrow-left">
                                    <span class="sr-only">Toggle menubar</span>
                                    <span class="hamburger-bar"></span>
                                </i>
                            </a>
                        </li>
                        <li class="nav-item hidden-sm-down" id="toggleFullscreen">
                            <a class="nav-link icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">
                                <span class="sr-only">Toggle fullscreen</span>
                            </a>
                        </li>
                        <li class="nav-item hidden-float">
                            <a class="nav-link icon md-search" data-toggle="collapse" href="#"
                                data-target="#site-navbar-search" role="button">
                                <span class="sr-only">Toggle Search</span>
                            </a>
                        </li>

                    </ul>
                    <!-- End Navbar Toolbar -->
                    <!-- Navbar Toolbar Right -->
                    <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                        <li class="nav-item hidden-float" style="margin-top:15px;margin-right:10px;">

                            <img alt=""
                                src="{{ file_exists(public_path('uploads/logo' . companyInfo()->logo)) ? asset('uploads/logo' . companyInfo()->logo) : '' }}"
                                style="height: 2.286rem;background-color:#fff; " title="{{ userCompanyName() }}">


                        </li>
                        @if (Auth::user()->role->permissions->contains('constant', 'group_access'))
                            <li class="nav-item hidden-float" style="margin-top:15px;">
                                <select class="form-control " id="cpny" onchange="setcpny()">
                                    @php
                                        $companies = companies();
                                    @endphp

                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $company->id == session('company_id') ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </li>
                        @else
                            <li class="nav-item hidden-sm-down" id="toggleFullscreen">
                                <a class="nav-link " href="#" role="button" style="font-size: 16px;">
                                    {{ userCompanyName() }}
                                </a>
                            </li>
                        @endif


                        {{-- <li class="nav-item hidden-float" style="margin-top:15px;">
                  <select class="form-control " id="fiscalyear" onchange="setfy()">
                   <option  >- {{__('Fiscal Year')}} -</option>


                   @for ($i = 2016; $i <= date('Y'); $i++)

                   <option value="{{$i}}">{{$i}}</option>
                   @endfor
                 </select>

               </li> --}}
                        {{-- <li class="nav-item dropdown">
                   <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" data-animation="scale-up"
                   aria-expanded="false" role="button">
                     <span class="flag-icon flag-icon-us"></span>
                   </a>
                   <div class="dropdown-menu" role="menu">
                     <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                       <span class="flag-icon flag-icon-gb"></span> English</a>
                     <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                       <span class="flag-icon flag-icon-fr"></span> French</a>
                     <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                       <span class="flag-icon flag-icon-cn"></span> Chinese</a>
                     <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                       <span class="flag-icon flag-icon-de"></span> German</a>
                     <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                       <span class="flag-icon flag-icon-nl"></span> Dutch</a>
                   </div>
                 </li> --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
                                data-animation="scale-up" role="button">
                                <span class="avatar avatar-online">
                                    <img src="{{ file_exists(public_path('uploads/avatar' . Auth::user()->image)) ? asset('uploads/avatar' . Auth::user()->image) : (Auth::user()->sex == 'M' ? asset('global/portraits/male-user.png') : asset('global/portraits/female-user.png')) }}"
                                        alt="">
                                    <i></i>
                                </span>
                            </a>
                            <div class="dropdown-menu" role="menu">

                                <a class="dropdown-item" href="#" role="menuitem"><i class="icon md-email"
                                        aria-hidden="true"></i> {{ auth()->user()->email }} </a>

                                <a class="dropdown-item" href="{{ url('userprofile') }}" role="menuitem"><i
                                        class="icon md-account" aria-hidden="true"></i> Profile</a>
                                @if (Auth::user()->role->permissions->contains('constant', 'edit_settings'))
                                    <a class="dropdown-item" href="{{ url('settings') }}" role="menuitem"><i
                                            class="icon md-settings" aria-hidden="true"></i> Settings</a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" role="menuitem" onclick="
                                                     document.getElementById('logout-form').submit();"><i
                                        class="icon md-power" aria-hidden="true"></i> {{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @if (Auth::user()->role->permissions->contains('constant', 'edit_settings') || Auth::user()->role->permissions->contains('constant', 'payroll_setting') || Auth::user()->role->permissions->contains('constant', 'workflows'))
                            <li class="nav-item dropdown">
                                <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Settings"
                                    aria-expanded="false" data-animation="scale-up" role="button">
                                    <i class="icon md-settings" aria-hidden="true" style="font-size: 24px;"></i>

                                </a>
                                <div class="dropdown-menu" role="menu">

                                    @if (Auth::user()->role->permissions->contains('constant', 'edit_settings'))
                                        <a class="dropdown-item" href="{{ url('settings') }}" role="menuitem">General
                                            Settings</a>

                                        <div class="dropdown-divider"></div>
                                    @endif
                                    @if (Auth::user()->role->permissions->contains('constant', 'payroll_setting'))
                                        <a class="dropdown-item" href="{{ url('payrollsettings') }}" role="menuitem">
                                            Payroll
                                            Settings</a>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    @if (Auth::user()->role->permissions->contains('constant', 'workflows'))
                                        <a class="dropdown-item" href="{{ url('workflows') }}" role="menuitem">
                                            Workflow
                                            Settings</a>
                                </div>
                        @endif
                        </li>
    @endif

    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Notifications" aria-expanded="false"
            data-animation="scale-up" role="button">
            <i class="icon md-notifications" aria-hidden="true"></i>
            @if (count(Auth::user()->unreadNotifications))
                <span class="tag tag-pill tag-danger up">{{ count(Auth::user()->unreadNotifications) }}</span>
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
            <div class="dropdown-menu-header">
                <h5>NOTIFICATIONS</h5>
                @if (count(Auth::user()->unreadNotifications))
                    <span class="tag tag-round tag-danger">New {{ count(Auth::user()->unreadNotifications) }}</span>
                @endif
            </div>
            <div class="list-group">
                <div data-role="container">
                    <div data-role="content">
                        @foreach (Auth::user()->unreadNotifications as $notification)
                            <a class="list-group-item dropdown-item notificationbutton"
                                href="{{ isset($notification->data['action']) ? $notification->data['action'] : '#' }}"
                                role="menuitem" id="{{ $notification->id }}">
                                <div class="media">
                                    <div class="media-left p-r-10">
                                        <i class="icon {{ isset($notification->data['icon']) ? $notification->data['icon'] : '' }} bg-red-600 white icon-circle"
                                            aria-hidden="true"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="media-heading">
                                            {{ isset($notification->data['type']) ? $notification->data['type'] : '' }}
                                        </h6>
                                        <time class="media-meta"
                                            datetime="{{ $notification->created_at }}">{{ $notification->created_at->diffForHumans() }}</time>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="dropdown-menu-footer">
                <a class="dropdown-menu-footer-btn" href="javascript:void(0)" role="button">
                    <i class="icon md-settings" aria-hidden="true"></i>
                </a>
                <a class="dropdown-item" href="{{ url('userprofile/notifications') }}" role="menuitem">
                    All notifications
                </a>
                <a class="dropdown-item" href="{{ url('userprofile/clear_notifications') }}" role="menuitem">
                    Clear notifications
                </a>
            </div>
        </div>
    </li>
    </ul>
    <!-- End Navbar Toolbar Right -->
    </div>
    <!-- End Navbar Collapse -->
    <!-- Site Navbar Seach -->
    <div class="collapse navbar-search-overlap" id="site-navbar-search">
        <form role="search">
            <div class="form-group">
                <div class="input-search">
                    <i class="input-search-icon md-search" aria-hidden="true"></i>
                    {{-- <input type="text" class="form-control" name="site-search" placeholder="Search..."> --}}
                    <select style="height:50px;" id="global_menu_search">

                    </select>
                    <button type="button" class="input-search-close icon md-close" data-target="#site-navbar-search"
                        data-toggle="collapse" aria-label="Close"></button>
                </div>
            </div>
        </form>
    </div>
    <!-- End Site Navbar Seach -->
    </div>
    </nav>


    {{-- @if (Auth::user()->role->manages == 'dr')
    @include('layouts.lmnav')
@else --}}
    @include('layouts.nav')
    {{-- @endif --}}
    @endif
{{-- <div style="margin-top: 10px; margin-bottom:10px;">
    <span>
    <img src="{{ asset('hcm.png') }}" alt="image" srcset="" class="img-fluid lsetf_logo">
    </span>
</div> --}}
    <div class='loader_load-container hidden animation_loader'>

        <div class='loader_load'>
            <img alt="" src="{{ asset('hcm.png') }}" id="hcmlogo">
            <div class="progress-dot" style="background: red;width: 20px;height:20px;"></div>
            <div class="progress-dot" style="background: green;width: 20px;height:20px;"></div>
            <div class="progress-dot" style="background: blue;width: 20px;height:20px;"></div>
            <div class="progress-dot" style="background: purple;width: 20px;height:20px;"></div>
            <div class="progress-dot" style="background: teal;width: 20px;height:20px;"></div>
            <div class="progress-dot" style="background: red;width: 20px;height:20px;"></div>
            <div class="progress-dot" style="background: green;width: 20px;height:20px;"></div>
            <div class="progress-dot" style="background: blue;width: 20px;height:20px;"></div>
            <div class="progress-dot" style="background: purple;width: 20px;height:20px;"></div>
            <div class="progress-dot" style="background: teal;width: 20px;height:20px;"></div>

            <div class='loader_load--text'></div>
        </div>
    </div>
    @yield('content')

    @if (!request()->filled('excel'))
        <!-- Footer -->
        <footer class="site-footer">

            <div class="site-footer-legal">

                © {{ date('Y') }}<a href=""> Snapnet </a>


            </div>

        </footer>
        <!-- Core  -->
        <script src="{{ asset('global/vendor/babel-external-helpers/babel-external-helpers.js') }}"></script>
        <script src="{{ asset('global/vendor/jquery/jquery.js') }}"></script>
        <script src="{{ asset('global/vendor/tether/tether.js') }}"></script>
        <script src="{{ asset('global/vendor/bootstrap/bootstrap.js') }}"></script>
        <script src="{{ asset('global/vendor/animsition/animsition.js') }}"></script>
        <script src="{{ asset('global/vendor/mousewheel/jquery.mousewheel.js') }}"></script>
        <script src="{{ asset('global/vendor/asscrollbar/jquery-asScrollbar.js') }}"></script>
        <script src="{{ asset('global/vendor/asscrollable/jquery-asScrollable.js') }}"></script>
        <script src="{{ asset('global/vendor/waves/waves.js') }}"></script>
        <!-- Plugins -->
        <script src="{{ asset('global/vendor/switchery/switchery.min.js') }}"></script>
        <script src="{{ asset('global/vendor/intro-js/intro.js') }}"></script>
        <script src="{{ asset('global/vendor/screenfull/screenfull.js') }}"></script>
        <script src="{{ asset('global/vendor/slidepanel/jquery-slidePanel.js') }}"></script>
        <script src="{{ asset('global/vendor/tablesaw/tablesaw.jquery.js') }}"></script>
        <script src="{{ asset('global/vendor/slidepanel/jquery-slidePanel.js') }}"></script>
        <script src="{{ asset('global/vendor/aspaginator/jquery.asPaginator.min.js') }}"></script>
        <script src="{{ asset('global/vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>
        <script src="{{ asset('global/vendor/bootbox/bootbox.js') }}"></script>
        <!-- Scripts -->
        <script src="{{ asset('global/js/State.js') }}"></script>
        <script src="{{ asset('global/js/Component.js') }}"></script>
        <script src="{{ asset('global/js/Plugin.js') }}"></script>
        <script src="{{ asset('global/js/Base.js') }}"></script>
        <script src="{{ asset('global/js/Config.js') }}"></script>
        <script src="{{ asset('assets/js/Section/Menubar.js') }}"></script>
        <script src="{{ asset('assets/js/Section/Sidebar.js') }}"></script>
        <script src="{{ asset('assets/js/Section/PageAside.js') }}"></script>
        <script src="{{ asset('assets/js/Plugin/menu.js') }}"></script>
        <script src="{{ asset('global/js/config/colors.js') }}"></script>
        <script src="{{ asset('assets/js/config/tour.js') }}"></script>

        <script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
        <script>
            Config.set('assets', '{{ asset('assets') }}');

        </script>
        <script src="{{ asset('assets/js/Site.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/asscrollable.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/slidepanel.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/switchery.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/tablesaw.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/sticky-header.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/action-btn.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/asselectable.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/editlist.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/aspaginator.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/animate-list.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/jquery-placeholder.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/material.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/selectable.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/bootbox.js') }}"></script>
        <script src="{{ asset('assets/js/BaseApp.js') }}"></script>
        <script src="{{ asset('assets/js/App/Contacts.js') }}"></script>
        <script src="{{ asset('assets/examples/js/apps/contacts.js') }}"></script>

        <script src="{{ asset('global/vendor/select2/select2.full.min.js') }}"></script>
        <script src="{{ asset('global/vendor/toastr/toastr.js') }}"></script>
        <script src="{{ asset('global/vendor/notie/notie.min.js') }}"></script>
        <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/bootstrap-datepicker.js') }}"></script>

        @yield('scripts')
        <script type="text/javascript" src="{{ asset('assets/js/jquery.thooClock.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                $('.datepicker_noprevious').datepicker({
                    format: 'yyyy-mm-d',
                    startDate: new Date,
                    autoclose: true,
                    closeOnDateSelect: true
                })

                @if (Auth::user()->leave_plans_count < 1)
                    {{-- notie.confirm('You have not created a leave plan. Do you want to create it right now?', 'Yes','Later', function () { --}}
                    {{-- this.location.href = '{{url('leave/myrequests')}}'; --}}
                    {{-- }, function () { --}}
                    {{-- notie.alert(3, 'Thank You', 1.5); --}}
                    {{-- }); --}}
                @endif

            });

            function ajaxStart() {
                toastr.remove();
                toastr.info('Processing ...');
                $('.btn').attr('disabled', true);

            }

            function ajaxStop() {
                $('.btn').attr('disabled', false);

                toastr.info('Done.');

            }

            function selectAjax(id, url) {
                $(`#${id}`).select2({
                    ajax: {
                        delay: 250,
                        processResults: function(data) {

                            return {

                                results: data
                            };
                        },


                        url: function(params) {
                            return url;
                        }

                    }
                });
            }

            // global Ajaxstart end
            $(document).ajaxStart(function() {
                ajaxStart();
                document.getElementById("loader").style.display = "block";
            }).ajaxStop(function() {
                ajaxStop();
                document.getElementById("loader").style.display = "none";
            });
            $(function() {


                $('#clockin').thooClock();

                setInterval(function() {

                    $('#time').html(new Date(new Date().getTime()).toLocaleTimeString());


                }, 1000);

                $('#global_menu_search').change(function() {

                    if ($(this).val() != 0) {

                        window.location = $(this).val();
                    }
                });

                $("#global_menu_search").select2({
                    width: '97%',
                    border: 'none',
                    dropdownCssClass: "bigdrop",
                    placeholder: 'Enter a menu item to search'
                });


                $(".site-menubar-body a").each(function(index, element) {
                    if (index == 0) {
                        $('#global_menu_search').append(
                            '<option selected value="0">-Enter a menu item to search-</option>');
                    }

                    $('#global_menu_search').append(
                        `<option value="${element.href === 'javascript:void(0)' ? '{{ url('/') }}' : element.href}">${element.text}</option>`
                        );

                })


                $(document).on('shown.bs.dropdown', '.table-responsive', function(e) {
                    // The .dropdown container
                    var $container = $(e.target);
                    // Find the actual .dropdown-menu
                    var $dropdown = $container.find('.dropdown-menu');
                    if ($dropdown.length) {
                        // Save a reference to it, so we can find it after we've attached it to the body
                        $container.data('dropdown-menu', $dropdown);
                    } else {
                        $dropdown = $container.data('dropdown-menu');
                    }
                    $dropdown.css('top', ($container.offset().top + $container.outerHeight()) + 'px');
                    $dropdown.css('left', $container.offset().left + 'px');
                    $dropdown.css('position', 'absolute');
                    $dropdown.css('display', 'block');
                    $dropdown.appendTo('body');
                });
                $(document).on('hide.bs.dropdown', '.table-responsive', function(e) {
                    // Hide the dropdown menu bound to this button
                    $(e.target).data('dropdown-menu').css('display', 'none');
                });
            });
            $(function() {
                $(document).on('click', '.notificationbutton', function(event) {
                    event.preventDefault();
                    href = $(this).attr('href');
                    id = $(this).attr('id');
                    $.get('{{ url('userprofile/clear_notification?notification_id=') }}' + id,
                        function(data, status, xhr) {


                        });
                    window.location = href;
                });
            });

            function postData(formData, url, reload = false) {
                $.post(url, formData, function(data, status, xhr) {
                    if (data.status == 'success') {

                        if (reload) {
                            $("#ldr").load(sessionStorage.getItem('href'));
                            $('.modal').modal('hide');
                        }
                        toastr.remove();
                        return toastr.success(data.message);
                    }
                    return toastr.error(data.message);
                })
            }

        </script>
        <script type="text/javascript">
            $(function() {

                $('.site-menu-item a[href*="{{ Request::url() }}"]').parent().addClass('active');
                $('.site-menu-item a[href*="{{ Request::url() }}"]').parent().parent().parent().addClass(
                    'active').addClass('open');
            });


            function showCourses() {
                $('#course-modal').modal();
            }

            // $(function () {
            //     $('#chatter').hide();

            //     $("#chatShow").click(function () {
            //         $('#chatter').slideDown();
            //         $('#chatShow').hide();
            //         $('#chatHide').show();
            //         // $('.wc-header').append('<button class="btn">Test</button>');
            //     });

            //     $("#chatHide").click(function () {
            //         $('#chatter').slideUp();
            //         $('#chatHide').hide();
            //         $('#chatShow').show();
            //     });

            //     $('iframe').load(function () {
            //         $('iframe').contents().find("head")
            //             .append($("<style type= 'text/css'> .wc-header{background-color:#008B8B !important; }</style>"
            //             ));
            //     });

            // });


            // var BotfuelWebChat =
            //     {
            //         init: function (options) {

            //             if (typeof BotChat != 'undefined') {
            //                 const params = BotChat.queryParams(location.search);
            //                 var div = document.createElement('div');
            //                 div.id = 'bot';
            //                 div.style.width = options.size.width + "px";
            //                 div.style.height = options.size.height + "px";
            //                 div.style.position = "relative";
            //                 document.body.appendChild(div);

            //                 BotChat.App({
            //                     bot: {id: 'botid'},
            //                     locale: params['locale'],
            //                     resize: 'detect',
            //                     user: {id: 'userid'},
            //                     directLine: {
            //                         secret: options.appSecret,
            //                         token: options.appToken
            //                     }
            //                 }, div);


            //             }
            //         }
            //     };


            // //
            // (function () {


            //     var div = document.createElement("div");
            //     document.getElementsByTagName('body')[0].appendChild(div);
            //     div.outerHTML = "<div id='botDiv' class='no-print' style='font-family: comic sans ms; height: 38px; position: fixed; bottom: 0; right: 0; z-index: 1000; background-color: #fff; float-right; border-radius:8px'><div id='botTitleBar' style='height: 45px; width: 600px; position:fixed; cursor: pointer; background-color: #03a9f4; color: #fff; padding: 8px 15px; font-size: default'></div><iframe width='350px' height='400px' style='font-family: comic sans ms;' src='https://webchat.botframework.com/embed/HCMatrix?s=X8Ns5_V16cw.gUa0xNXRkN2gfikioA6kH5ZQ5vyXnAF6IxuDaQGm7uo'></iframe></div>";

            //     document.querySelector('body').addEventListener('click', function (e) {
            //         e.target.matches = e.target.matches || e.target.msMatchesSelector;
            //         if (e.target.matches('#botTitleBar')) {
            //             var botDiv = document.querySelector('#botDiv');
            //             botDiv.style.height = botDiv.style.height == '400px' ? '38px' : '400px';
            //         }
            //         ;
            //     });

            //     $('#botTitleBar').html('HCMatrix Bot')


            // })();

            // BotfuelWebChat.init(
            //     {
            //         appToken: 'directline secret',
            //         size: {width: 500, height: 600}
            //     })

        </script>
        <script type="text/javascript">
            $(function() {

                $('.site-menu-item a[href*="{{ Request::url() }}"]').parent().addClass('active');
                $('.site-menu-item a[href*="{{ Request::url() }}"]').parent().parent().parent().addClass(
                    'active').addClass('open');
            });

            function showCourses() {
                $('#course-modal').modal();
                fetchCourses();
            }

            function newCourseRow() {
                //full-name , access-link
                var $el = $($('#loop-course').html()); //.clone();
                // $el.show();
                return $el;
            }

            function generateCourseLoop(list) {
                var $parentEl = $('#parent-course');
                $parentEl.html('');
                list.forEach(function(v, k) {
                    var $el = newCourseRow();
                    $el.find('#full-name').html(v.fullname);
                    $el.find('#access-link').attr('href',
                        'http://elearning.thehcmatrix.com/hcm_onboard/index.php?endpoint=autologin&username={{ Auth::user()->emp_num }}&course=' +
                        v.id);
                    $parentEl.append($el);
                });
            }


            /* var _Network_state = true;
             function updateIndicator() {
                 // Show a different icon based on offline/online
                 if (navigator.onLine) { // true|false
                     // ... do other stuff
                     _Network_state = true;
                 } else {
                     // ... do ot
                     alert('You are currently offline, please connect to the internet');
                     window.location.reload();
                     _Network_state = false;

                 }
                 console.info(_Network_state ? 'Online' : 'Offline');
             }
             // Update the online status icon based on connectivity
             window.addEventListener('online',  updateIndicator);
             window.addEventListener('offline', updateIndicator);
             updateIndicator();*/


            function fetchCourses() {
                var $ldg = $('#loading-indicator');
                $.ajax({
                    url: '{{ route('app.get', ['get-course-categories']) }}',
                    type: 'GET',
                    success: function(response) {
                        generateCourseLoop(response.data);
                        $ldg.hide();
                    }
                });
            }

        </script>


        <script>
            (function($) {
                $(function() {
                    @if (request()->filled('js-trigger-click-id') && is_array(request()->get('js-trigger-click-id')))
                        @foreach (request()->get('js-trigger-click-id') as $id)
                            setTimeout(function () {
                            $('#{{ $id }}').trigger('click');
                            }, 1000);
                        @endforeach
                    @endif
                    @if (session()->has('message'))
                        @if (session()->has('error') && session()->get('error'))
                            toastr.error('{{ session()->get('message') }}');
                        @else
                            toastr.success('{{ session()->get('message') }}');
                        @endif
                    @endif
                });
            })(jQuery);

        </script>

        @yield('script-footer','')

    @endif
</body>

</html>
