<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Regirtration for a new company">
    <meta name="author" content="">
    <title>HCMatrix | Register</title>
    <link rel="apple-touch-icon" href="{{asset('assets/images/apple-touch-icon.png')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{asset('global/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('global/css/bootstrap-extend.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/site.min.css')}}">
    <!-- Plugins -->
    <link rel="stylesheet" href="{{asset('global/vendor/animsition/animsition.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/asscrollable/asScrollable.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/switchery/switchery.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/intro-js/introjs.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/slidepanel/slidePanel.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/flag-icon-css/flag-icon.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/waves/waves.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/jquery-wizard/jquery-wizard.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/formvalidation/formValidation.css')}}">
    <!-- Fonts -->
    <link rel="stylesheet" href="{{asset('global/fonts/material-design/material-design.min.css')}}">
    <link rel="stylesheet" href="{{asset('global/fonts/brand-icons/brand-icons.min.css')}}">
    <link rel="stylesheet" href="{{asset('global/fonts/font-awesome/font-awesome.css')}}">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>

    <script src="{{asset('global/vendor/media-match/media.match.min.js')}}"></script>
    <script src="{{asset('global/vendor/respond/respond.min.js')}}"></script>
    <![endif]-->
    <!-- Scripts -->
    <script src="{{asset('global/vendor/breakpoints/breakpoints.js')}}"></script>
    <script>
        Breakpoints();
    </script>
    <style type="text/css">
        .hidden{
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
        .progress-dot{
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

        #hcmlogo{
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
</head>

<body class="animsition   " style="padding-top: 0px;padding-bottom: 0px;">

<div class='loader_load-container hidden animation_loader'>

    <div class='loader_load'>
        <img src="{{asset('hcm.png')}}" id="hcmlogo">
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
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->


<!-- Page -->
<div class="page main-page" style="min-height:100%;">
    <div class="page-header">
        <h1 class="page-title">Start Trial</h1>


    </div>
    <div class="page-content container-fluid">
        <div class="row">

            <div class="col-xs-12 ">
                <!-- Panel Wizard Form Container -->
                <div class="panel" id="exampleWizardFormContainer">
                    <div class="panel-heading">
                        <h3 class="panel-title">HCMatrix Trial Registration</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Steps -->
                        <div class="pearls row">
                            <div class="pearl current col-xs-4">
                                <div class="pearl-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                                <span class="pearl-title">User Information</span>
                            </div>
                            <div class="pearl col-xs-4">
                                <div class="pearl-icon"><i class="fa fa-building" aria-hidden="true"></i></div>
                                <span class="pearl-title">Company Information</span>
                            </div>
                            <div class="pearl col-xs-4">
                                <div class="pearl-icon"><i class="icon md-check" aria-hidden="true"></i></div>
                                <span class="pearl-title">Confirmation</span>
                            </div>
                        </div>
                        <!-- End Steps -->
                        <!-- Wizard Content -->
                        <form class="wizard-content" id="exampleFormContainer"  action="{{ route('register_company') }}" method="post">

                            <div class="wizard-pane active" role="tabpanel">
                                <div class="col-md-4">
                                    <div class="form-group form-material">
                                        <label class="form-control-label" for="inputUserNameOne">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required="required">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-material">
                                        <label class="form-control-label" for="inputUserNameOne">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required="required">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-material">
                                        <label class="form-control-label" for="inputPasswordOne">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               required="required">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-material">
                                        <label class="form-control-label" for="inputUserNameOne">Employee Number</label>
                                        <input type="text" class="form-control" id="emp_num" name="emp_num" required="required">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-material">
                                        <label class="form-control-label" for="inputUserNameOne">Gender</label>
                                        <select class="form-control" id="gender" name="gender" required="required">
                                            <option value=""></option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-material">
                                        <label class="form-control-label" for="inputUserNameOne">Hire Date</label>
                                        <input type="text" class="form-control datepicker" readonly id="hiredate" name="hiredate" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-material">
                                        <label class="form-control-label" for="inputUserNameOne">Grade</label>
                                        <input type="text" class="form-control"  id="grade" name="grade" required="required">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-material">
                                        <label class="form-control-label" for="inputUserNameOne">Department</label>
                                        <input type="text" class="form-control"  id="department" name="department" required="required">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-material">
                                        <label class="form-control-label" for="inputUserNameOne">Job Role</label>
                                        <input type="text" class="form-control"  id="job_role" name="job_role" required="required">
                                    </div>
                                </div>


                            </div>
                            <div class="wizard-pane" id="exampleBillingOne" role="tabpanel">
                                <div class="form-group form-material">
                                    <label class="form-control-label" for="inputCardNumberOne">Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name">
                                </div>
                                <div class="form-group form-material">
                                    <label class="form-control-label" for="inputCVVOne">Email</label>
                                    <input type="text" class="form-control" id="company_email" name="company_email" placeholder="Company Email">
                                </div>
                                <div class="form-group form-material">
                                    <label class="form-control-label" for="inputCVVOne">Address</label>
                                    <input type="text" class="form-control" id="company_address" name="company_address" placeholder="Company Address">
                                </div>
                            </div>
                            <div class="wizard-pane" id="exampleGettingOne" role="tabpanel">
                                <div class="text-xs-center m-y-20">
                                    <h4>Please confirm your details.</h4>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">

                                        <ul class="list-group list-group-bordered">
                                            <li class="list-group-item active">User Information</li>
                                            <li class="list-group-item" id="e_first_name"></li>
                                            <li class="list-group-item" id="e_last_name"></li>
                                            <li class="list-group-item" id="e_email"></li>
                                            <li class="list-group-item" id="e_emp_num"></li>
                                            <li class="list-group-item" id="e_gender"></li>
                                            <li class="list-group-item" id="e_hiredate"></li>
                                            <li class="list-group-item" id="e_grade"></li>
                                            <li class="list-group-item" id="e_department"></li>
                                            <li class="list-group-item" id="e_job_role"></li>
                                        </ul>

                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-bordered">
                                            <li class="list-group-item active">Company Information</li>
                                            <li class="list-group-item" id="e_company_name"></li>
                                            <li class="list-group-item" id="e_company_email"></li>
                                            <li class="list-group-item" id="e_company_address"></li>
                                        </ul>
                                    </div>
                                </div>
                                @csrf

                            </div>

                        </form>
                        <!-- Wizard Content -->
                    </div>
                </div>
                <!-- End Panel Wizard Form Container -->
            </div>
        </div>

    </div>
</div>
<!-- End Page -->
<!-- Footer -->

<!-- Core  -->
<script src="{{asset('global/vendor/babel-external-helpers/babel-external-helpers.js')}}"></script>
<script src="{{asset('global/vendor/jquery/jquery.js')}}"></script>
<script src="{{asset('global/vendor/tether/tether.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap/bootstrap.js')}}"></script>
<script src="{{asset('global/vendor/animsition/animsition.js')}}"></script>
<script src="{{asset('global/vendor/mousewheel/jquery.mousewheel.js')}}"></script>
<script src="{{asset('global/vendor/asscrollbar/jquery-asScrollbar.js')}}"></script>
<script src="{{asset('global/vendor/asscrollable/jquery-asScrollable.js')}}"></script>
<script src="{{asset('global/vendor/waves/waves.js')}}"></script>
<!-- Plugins -->
<script src="{{asset('global/vendor/switchery/switchery.min.js')}}"></script>
<script src="{{asset('global/vendor/intro-js/intro.js')}}"></script>
<script src="{{asset('global/vendor/screenfull/screenfull.js')}}"></script>
<script src="{{asset('global/vendor/slidepanel/jquery-slidePanel.js')}}"></script>
<script src="{{asset('global/vendor/formvalidation/formValidation.js')}}"></script>
<script src="{{asset('global/vendor/formvalidation/framework/bootstrap.js')}}"></script>
<script src="{{asset('global/vendor/matchheight/jquery.matchHeight-min.js')}}"></script>
<script src="{{asset('global/vendor/jquery-wizard/jquery-wizard.js')}}"></script>
<!-- Scripts -->
<script src="{{asset('global/js/State.js')}}"></script>
<script src="{{asset('global/js/Component.js')}}"></script>
<script src="{{asset('global/js/Plugin.js')}}"></script>
<script src="{{asset('global/js/Base.js')}}"></script>
<script src="{{asset('global/js/Config.js')}}"></script>
<script src="{{asset('assets/js/Section/Menubar.js')}}"></script>
<script src="{{asset('assets/js/Section/Sidebar.js')}}"></script>
<script src="{{asset('assets/js/Section/PageAside.js')}}"></script>
<script src="{{asset('assets/js/Plugin/menu.js')}}"></script>
<!-- Config -->
<script src="{{asset('global/js/config/colors.js')}}"></script>
<script src="{{asset('assets/js/config/tour.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('global/js/Plugin/bootstrap-datepicker.js')}}"></script>
<script>

    function triggerLoader(){
        document.querySelector('.animation_loader').classList.remove('hidden')
        document.querySelector('.main-page').classList.add('hidden')
    }

    $(document).ready(function () {

        document.querySelectorAll(".btn").forEach((elem)=>{
            elem.addEventListener("click", function(){
                console.log(this.getAttribute('data-wizard'))
                if(this.getAttribute('data-wizard')==='finish'){

                    triggerLoader();
                }
            });
        })
        $('.datepicker').datepicker({
            format: 'yyyy-mm-d',
            autoclose: true,
            closeOnDateSelect: true
        });
    });
    Config.set('assets', '{{asset('assets')}}');

</script>
<!-- Page -->
<script src="{{asset('assets/js/Site.js')}}"></script>
<script src="{{asset('global/js/Plugin/asscrollable.js')}}"></script>
<script src="{{asset('global/js/Plugin/slidepanel.js')}}"></script>
<script src="{{asset('global/js/Plugin/switchery.js')}}"></script>
<script src="{{asset('global/js/Plugin/jquery-wizard.js')}}"></script>
<script src="{{asset('global/js/Plugin/matchheight.js')}}"></script>
<script src="{{asset('assets/examples/js/forms/wizard.js')}}"></script>
</body>
</html>
