<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content=" ePersman">
  <meta name="author" content="">
  <title>Login | {{ config('app.name', systemInfo()['name']) }}</title>
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
  <link rel="stylesheet" href="{{ asset('assets/examples/css/pages/login.css') }}">
  <!-- Fonts -->
  <link rel="stylesheet" href="{{ asset('global/fonts/material-design/material-design.min.css') }}">
  <link rel="stylesheet" href="{{ asset('global/fonts/brand-icons/brand-icons.min.css') }}">
  <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
  <style type="text/css">
      .curved{
        border-radius: 5px;
      }
      label{
        margin-bottom: .5rem;
      }
  </style>
  <!--[if lt IE 9]>
    <script src="../../../global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
  <!--[if lt IE 10]>
    <script src="../../../global/vendor/media-match/media.match.min.js"></script>
    <script src="../../../global/vendor/respond/respond.min.js"></script>
    <![endif]-->
  <!-- Scripts -->
  <script src="{{ asset('global/vendor/breakpoints/breakpoints.js') }}"></script>
  <script>
  Breakpoints();
  </script>
</head>
<body class="animsition page-login layout-full bg-orange-a100" style="background: url('{{asset('assets/images/login.jpg')}}');">
  <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
  <!-- Page -->
  <div class="page vertical-align text-xs-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
    <div class="page-content vertical-align-middle" style="border-radius: 10px;background-color: rgba(20, 26, 26, 0.7);">
      <div class="brand">
         <img class="brand-img" src="{{ asset('assets/images/logo.png') }}" alt="HCMatrix"> 
        <h2 class="brand-text" style="color: #fff;">{{systemInfo()['name']}}</h2>
      </div>
      <p class="font-size-20">RECRUIT | RETAIN | REWARD</p>
      <form method="POST" action="{{ route('login') }}" >
         @csrf
{{--        aria-label="{{ __('Login') }}"--}}
          @if ($errors->has('active'))
          <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
              {{ $errors->first('active') }}
          </div>
          @endif
        <div class="form-group form-material floating" data-plugin="formMaterial">
          <input type="email" class="form-control curved {{ $errors->has('email') ? ' is-invalid' : '' }}" id="inputEmail" name="email" value="{{ old('email') }}" required autofocus>
          <label class="floating-label" for="email">{{ __('E-Mail Address') }}</label>
          @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
        </div>
        <div class="form-group form-material floating" data-plugin="formMaterial">
          <input type="password" class="form-control curved {{ $errors->has('password') ? ' is-invalid' : '' }}" id="inputPassword" name="password" required>
          <label class="floating-label" for="inputPassword">{{ __('Password') }}</label>
          @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
        </div>
        <div class="form-group clearfix">
          <div class="checkbox-custom checkbox-inline checkbox-primary pull-xs-left">
            <input type="checkbox" id="inputCheckbox" name="remember"  {{ old('remember') ? 'checked' : '' }}>
            <label for="inputCheckbox">{{ __('Remember Me') }}</label>
          </div>
          <a class="pull-xs-right" style="color: #eb3c00;" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
        </div>
        <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>
      </form>
      <p class="text-center" style="text-align:center">- OR -</p>
     <a href="{{ url('/auth/microsoft') }}" class="btn  btn-block" style="background: #fff;color: #eb3c00;" ><img src="{{asset('assets/images/o365.png')}}">&nbsp;Sign in With Office365</a>
     <p>Still no account?  <a href="{{url('register')}}">Try out Hcmatrix</a></p> 
     <footer class="page-copyright page-copyright-inverse">
        <p>{{systemInfo()['name']}}</p>
        <p>© {{date('Y')}}. All RIGHT RESERVED.</p>

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
  <script src="{{ asset('global/vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>
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
  <script>
  Config.set('assets', '{{ asset('assets') }}');
  </script>
  <script src="{{ asset('assets/js/Site.js') }}"></script>
  <script src="{{ asset('global/js/Plugin/asscrollable.js') }}"></script>
  <script src="{{ asset('global/js/Plugin/slidepanel.js') }}"></script>
  <script src="{{ asset('global/js/Plugin/switchery.js') }}"></script>
 <script src="{{ asset('global/js/Plugin/jquery-placeholder.js') }}"></script>
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
