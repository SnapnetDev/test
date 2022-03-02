<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="bootstrap admin template">
  <meta name="author" content="">
  <title>Forgot password | {{ config('app.name', 'HCMatrix') }}</title>
  <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">
  <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
  <!-- Stylesheets -->
  <link rel="stylesheet" href="{{ asset('global/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('global/css/bootstrap-extend.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
  <!-- Plugins -->
  <link rel="stylesheet" href="{{ asset('global/vendor/animsition/animsition.css') }}">
  <link rel="stylesheet" href="{{ asset('global/vendor/asscrollable/asScrollable.css') }}">
  <link rel="stylesheet" href="{{ asset('global/vendor/switchery/switchery.css') }}">
  <link rel="stylesheet" href="{{ asset('global/vendor/intro-js/introjs.css') }}">
  <link rel="stylesheet" href="{{ asset('global/vendor/slidepanel/slidePanel.css') }}">
  <link rel="stylesheet" href="{{ asset('global/vendor/flag-icon-css/flag-icon.css') }}">
  <link rel="stylesheet" href="{{ asset('global/vendor/waves/waves.css') }}">

  <link rel="stylesheet" href="{{ asset('global/vendor/toastr/toastr.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/examples/css/pages/forgot-password.css') }}">
  <!-- Fonts -->
  <link rel="stylesheet" href="{{ asset('global/fonts/font-awesome/font-awesome.css') }}">
  <link rel="stylesheet" href="{{ asset('global/fonts/material-design/material-design.min.css') }}">
  <link rel="stylesheet" href="{{ asset('global/fonts/brand-icons/brand-icons.min.css') }}">
  
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
  <!--[if lt IE 9]>
    <script src="../../../global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
  <!--[if lt IE 10]>
    <script src="../../../global/vendor/media-match/media.match.min.js"></script>
    <script src="../../../global/vendor/respond/respond.min.js"></script>
    <![endif]-->
  <!-- Scripts -->
  <script src="../../../global/vendor/breakpoints/breakpoints.js"></script>
  <script>
  Breakpoints();
  </script>
</head>
<body class="animsition page-forgot-password layout-full">
  <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
  <!-- Page -->
  <div class="page vertical-align text-xs-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
     @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
    <div class="page-content vertical-align-middle">
      <h2>Forgot Your Password ?</h2>
      <p>Input your registered email to reset your password</p>
      <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}">
        @csrf
        <div class="form-group form-material floating" data-plugin="formMaterial">
          <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="inputEmail" name="email" value="{{ old('email') }}" required>
          <label class="floating-label" for="inputEmail">{{ __('E-Mail Address') }}</label>
        </div>
        
        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">{{ __('Send Password Reset Link') }}</button>
        </div>
      </form>
      <footer class="page-copyright">
        <p>HCMatrix</p>
        <p>© {{date('Y')}}. All RIGHT RESERVED.</p>
        {{-- <div class="social">
          <a href="javascript:void(0)">
            <i class="icon bd-twitter" aria-hidden="true"></i>
          </a>
          <a href="javascript:void(0)">
            <i class="icon bd-facebook" aria-hidden="true"></i>
          </a>
          <a href="javascript:void(0)">
            <i class="icon bd-dribbble" aria-hidden="true"></i>
          </a>
        </div> --}}
      </footer>
    </div>
  </div>
  <!-- End Page -->
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
  <!-- Config -->
  <script src="{{ asset('global/js/config/colors.js') }}"></script>
  <script src="{{ asset('assets/js/config/tour.js') }}"></script>
  <script>
  Config.set('assets', '../../assets');
  </script>
  <!-- Page -->
  <script src="{{ asset('assets/js/Site.js') }}"></script>
  <script src="{{ asset('global/js/Plugin/asscrollable.js') }}"></script>
  <script src="{{ asset('global/js/Plugin/slidepanel.js') }}"></script>
  <script src="{{ asset('global/js/Plugin/switchery.js') }}"></script>
  <script src="{{ asset('global/js/Plugin/material.js') }}"></script>
  <script>
  (function(document, window, $) {
    'use strict';
    var Site = window.Site;
    $(document).ready(function() {
      Site.run();
    });
  })(document, window, jQuery);
  </script>
</body>
</html>
