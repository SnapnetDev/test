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
                            <div class="list-group-item list-group-item-success "  >
                                <h4 class="list-group-item-heading"  style="color: white">Employees Import</h4>
                                <p class="list-group-item-text"  style="color: white">Import Employee data of Employees in your organization</p>
                            </div>
                            <div class="list-group-item list-group-item-success "  >
                                <h4 class="list-group-item-heading" style="color: white">Leave Policy Setup</h4>
                                <p class="list-group-item-text" style="color: white">Setup the leave policy of your organization</p>
                            </div>
                            <a class="list-group-item active" href="javascript:void(0)">
                                <h4 class="list-group-item-heading">Payroll Policy Setup</h4>
                                <p class="list-group-item-text">Setup the payroll policy of your organization</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-9">

                        <div class="panel panel-info panel-line">
                            <div class="panel-heading">
                                <h3 class="panel-title">Payroll Policy Settings</h3>
                                <div class="panel-actions">

                                </div>
                            </div>
                            <form action="{{url('cr_payroll_policy')}}" method="post">
                                <div class="panel-body">
                                    <div class="col-md-6">
                                        @csrf
                                        <div class="form-group">

                                            <input type="hidden" name="source" value="onboarding">
                                        </div>
                                        <div class="form-group">
                                            <h4>Basic Pay Percentage</h4>
                                            <input type="text" name="basic_pay" class="form-control"
                                                   required>
                                            <h4>Transport Percentage</h4>
                                            <input type="text" name="transport" class="form-control" required>
                                            <h4>Housing Percentage</h4>
                                            <input type="text" name="housing" class="form-control"
                                                   required>
                                        </div>
                                        <br>


                                        <br>



                                    </div>

                                </div>
                                <div class="panel-footer">
                                    <div class="form-group">
                                        <button class="btn btn-info" type="submit">Save Changes</button>
                                    </div>
                                </div>
                            </form>
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
    <script type="text/javascript">

        $('.datepicker').datepicker({
            autoclose: true
        });
        $('.bstoggle').bootstrapToggle({
            on: 'Yes',
            off: 'No',
            onstyle: 'info',
            offstyle: 'default'
        });
        $(document).on('submit', '#payrollPolicyForm', function (event) {
            event.preventDefault();
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{url('register-payroll-policy')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    location.reload();
                    // toastr.success("Changes saved successfully", 'Success');

                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr.error(valchild[0]);
                        });
                    });
                }
            });

        });

    </script>
@endsection
