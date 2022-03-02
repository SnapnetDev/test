@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Gochi+Hand" rel="stylesheet">

    <style media="screen">


        #tree {
            width: 100%;
            height: 100%;
        }
    </style>

@endsection
@section('content')
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">Company Onboarding</h1>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content container-fluid">
            <div class="row">

                <div class="col-md-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                            {{ session('success') }}
                        </div>
                    @elseif (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="col-md-3">
                        <div class="list-group list-group-gap">
                            <div class="list-group-item list-group-item-success " >
                                <h4 class="list-group-item-heading text-white" style="color: white">Jobroles Setup</h4>
                                <p class="list-group-item-text text-white" style="color: white">Setup the grade levels in your organization</p>
                            </div>
                            <div class="list-group-item list-group-item-success " >
                                <h4 class="list-group-item-heading text-white" style="color: white">Branches Setup</h4>
                                <p class="list-group-item-text text-white" style="color: white">Setup the Branches in your organization</p>
                            </div>
                            <div class="list-group-item list-group-item-success "  >
                                <h4 class="list-group-item-heading text-white" style="color: white">Department Setup</h4>
                                <p class="list-group-item-text text-white">Setup the various Job roles in your Organization</p>
                            </div>
                            <div class="list-group-item list-group-item-success "  >
                                <h4 class="list-group-item-heading" style="color: white">Job Roles Setup</h4>
                                <p class="list-group-item-text" style="color: white">Setup the various Job roles in your organization</p>
                            </div>
                            <a class="list-group-item active" href="javascript:void(0)">
                                <h4 class="list-group-item-heading">Employees Import</h4>
                                <p class="list-group-item-text">Import Employee data of Employees in your organization</p>
                            </a>
                            <a class="list-group-item disabled" href="javascript:void(0)">
                                <h4 class="list-group-item-heading">Leave Policy Setup</h4>
                                <p class="list-group-item-text">Setup the leave policy of your organization</p>
                            </a>
                            <a class="list-group-item disabled" href="javascript:void(0)">
                                <h4 class="list-group-item-heading">Payroll Policy Setup</h4>
                                <p class="list-group-item-text">Setup the payroll policy of your organization</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="panel panel-info  ">
                            <div class="panel-heading main-color-bg">
                                <h3 class="panel-title">Employees Setup</h3>
                            </div>

                            <div class="panel-body" style="padding-top: 15px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="list-group-item-text">Import the Employees in your organization. Click on the link below and fill as shown in the example by the right. <br>
                                            <a href="{{url('import/download_employee_template')}}">Download Template for Employees Import</a>
                                        </p>
                                        <form action="{{url('import')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                    <div class="form-group">
                                        <label for="">File to Upload</label>
                                        <input type="file" class="form-control"  name="template" required>
                                        <input type="hidden" name="type" value="employees">
                                        <input type="hidden" name="source" value="onboarding">

                                    </div>
                                        <button type="submit" class="btn btn-primary">
                                            Import Employees
                                        </button>
                                        </form>
                                    </div>
                                    <div class="col-md-6" style="overflow: scroll;">
                                        <img src="{{asset('assets/images/employee_import_demo.png')}}" alt="">
                                    </div>
                                </div>


                            </div>
                            <div class="panel-footer" style="">

                                <a href="#" class="btn btn-primary ">Previous</a>
                                <a href="#" class="btn btn-primary pull-right">Next</a>
                            </div>

                        </div>
                    </div>


                </div>
            </div>

        </div>


    </div>
@endsection
@section('scripts')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/raphael/raphael.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/balkan/orgchart.js')}}"></script>
    <script type="text/javascript">


    </script>
@endsection
