@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('global/vendor/webui-popover/webui-popover.min.css') }}">
    <style type="text/css">
        .btn-floating.btn-sm {

            width: 4rem;
            height: 4rem;

        }
    </style>
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Employee Expense Reimbursements')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Employee Expense Reimbursements')}}</li>
            </ol>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium" id="time"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content container-fluid">
            <div class="row" data-plugin="matchHeight" data-by-row="true">
                <!-- First Row -->

                <!-- End First Row -->
                {{-- second row --}}
                <div class="col-ms-12 col-xs-12 col-md-12">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">My Employee Expense Reimbursements</h3>
                            <div class="panel-actions">

                                <button class="btn btn-info" data-toggle="modal" data-target="#addEmployeeReimbursementModal">New Expense Reimbursements</button>

                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <thead>
                                    <tr>
                                        <th>Expense Reimbursement Type</th>
                                        <th>Title</th>
                                        <th>Expense Date</th>
                                        <th>Amount</th>
                                        <th>Approval Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @foreach($employee_reimbursements as $er)
                                            <td>{{$er->employee_reimbursement_type?$er->employee_reimbursement_type->name:""}}</td>
                                            <td>{{$er->title}}</td>
                                            <td>{{date("F j, Y", strtotime($er->expense_date))}}</td>

                                            <td>{{round($er->amount,2)}}</td>
                                            <td><span class=" tag   {{$er->status==0?'tag-warning':($er->status==1?'tag-success':'tag-danger')}}">{{$er->status==0?'pending':($er->status==1?'approved':'rejected')}}</span></td>

                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                                        <a data-id="{{ $er->id }}" style="cursor:pointer;"class="dropdown-item view-approval" id="{{$er->id}}" onclick="viewRequestApproval(this.id)"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Approval</a>

                                                        @if($er->attachment!='')
                                                            <a style="cursor:pointer;"class="dropdown-item" id="" href="{{url('employee_reimbursements/download?employee_reimbursement_id='.$er->id)}}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Document</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                    </tr>
                                    @endforeach

                                    </tbody>

                                </table>
                            </div>


                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- End Page -->
    @include('employee_reimbursements.modals.addrequest')
    {{-- Document Request Details Modal --}}
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="employeeReimbursementDetailsModal" aria-hidden="true" aria-labelledby="employeeReimbursementDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Employee Reimbursement Approval Details</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12" id="detailLoader">

                        </div>
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">


                        <!-- End Example Textarea -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{ asset('global/vendor/webui-popover/jquery.webui-popover.min.js') }}"></script>

    <script type="text/javascript">


        (function($){

            $(function(){

                $('.selecttwo').select2();

                ///module start///

                let check = 0;
                let vl = 0;

                function doValidate(v){
                    vl = v || vl;
                    check = vl - $('#leave_days_requested').val();
                    if (check < 0){

                        toastr.error('Your leave days cannot exceed your entitled days (' + $('#leave_days_requested').val() + ')');

                        //alert('Your leave days cannot exceed your entitled days (' + $('#leave_days_requested').val() + ')');
                    }
                    return {
                        check: check > 0,
                        value: check
                    };
                }


                function ajaxStart(){
                    toastr.info('Processing ...');
                }

                function ajaxStop(){
                    toastr.info('Done.');
                }


                function postForm($form,$api){

                    let promise = {
                        success:function(cb,inValue){
                            // cb(inValue);
                            console.log(this);
                            this._success = cb;
                            return promise;
                        },
                        before:function(cb){
                            this._before = cb;
                            return promise;
                        },
                        _before:function(){
                            return true;
                        },
                        _success:function(){
                            //
                        },///
                        _error:function(){
                            //
                        },
                        error:function(cb,inError){
                            // cb(inError);
                            this._error = cb;
                            return promise;
                        },
                        processSuccess:function(response){
                            this._success(response);
                        },
                        processError:function(responseError){
                            this._error(responseError);
                        },
                        processBefore:function(content){
                            return this._before(content);
                        }
                    };

                    if (promise.processBefore({})){

                        ajaxStart();

                        var form = $form;
                        var formdata = false;
                        if (window.FormData){
                            formdata = new FormData(form[0]);
                        }
                        $.ajax({
                            url         : $api,
                            data        : formdata ? formdata : form.serialize(),
                            cache       : false,
                            contentType : false,
                            processData : false,
                            type        : 'POST',
                            success     : function(data, textStatus, jqXHR){

                                // promise.success();
                                promise.processSuccess({
                                    data:data,
                                    textStatus:textStatus,
                                    jqXHR:jqXHR
                                });

                                ajaxStop();

                                // toastr.success("Changes saved successfully..",'Success');
                                // $('#addLeaveRequestModal').modal('toggle');

                                // location.reload();

                            },
                            error:function(data, textStatus, jqXHR){

                                promise.processSuccess({
                                    data:data,
                                    textStatus:textStatus,
                                    jqXHR:jqXHR
                                });

                                ajaxStop();

                                //  jQuery.each( data['responseJSON'], function( i, val ) {
                                //   jQuery.each( val, function( i, valchild ) {
                                //   toastr.error(valchild[0]);
                                // });
                                // });

                            }
                        });

                    }

                    return promise;
                }








                $('.input-daterange').datepicker({
                    autoclose: true,
                    format:'yyyy-mm-dd'
                });


                $(document).on('submit','#addLeaveRequestForm',function(event){
                    event.preventDefault();
                    initAddLeaveForm();
                });
                $(document).on('submit','#addLeavePlanForm',function(event){
                    event.preventDefault();
                    initAddLeavePlanForm();
                });


                function viewRequestApproval(employee_reimbursement_id)
                {
                    $(document).ready(function() {
                        $("#detailLoader").load('{{ url('/employee_reimbursements/get_details') }}?employee_reimbursement_id='+employee_reimbursement_id);
                        $('#employeeReimbursementDetailsModal').modal();
                    });

                }

                window.viewRequestApproval = viewRequestApproval;  //Export to global windows object.
                ///module stop///

            });

        })(jQuery);

        ////End - JQuery ...

    </script>

    <script type="text/javascript">

        function datePicker(){
            $('.date_picker').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd'
            });
        }

        $(document).ready(function() {

            datePicker();
            $('#addEmployeeReimbursementForm').submit(function(e){
                e.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url         : '{{url('employee_reimbursements')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){

                        toastr["success"]("Changes saved successfully",'Success');
                        $('#addEmployeeReimbursementModal').modal('toggle');
                        location.reload();
                    },
                    error:function(data, textStatus, jqXHR){
                        jQuery.each( data['responseJSON'], function( i, val ) {
                            jQuery.each( val, function( i, valchild ) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });



        });



    </script>
@endsection
