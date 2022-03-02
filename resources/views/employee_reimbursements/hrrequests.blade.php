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
                <form action="" id="filterForm">
                <div class="col-md-4"><div class="form-group"><label for="employee_name">Employee name</label><select name="emp" id="emp" style="width:100%;" class="form-control selecttwo" >
                            <option value="">-Select Employee-</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}" {{request()->user_id==$user->id?"selected":""}}>{{$user->name}}</option>
                            @endforeach
                        </select></div></div>
                <div class="col-md-4"><div class="form-group"><label for="date">Expense Type</label><select class="form-control"  id="employee_reimbursement_type_id" name="employee_reimbursement_type_id">
                            <option value="">-Select Expense Reimbursement Type-</option>
                            @foreach($employee_reimbursement_types as $ert)
                                <option value="{{$ert->id}}" {{request()->employee_reimbursement_type_id==$ert->id?"selected":""}}>{{$ert->name}}</option>
                            @endforeach</select></div></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="period">Period</label>
                    <div class="input-daterange input-group" id="datepicker">
                    <span class="input-group-addon">
                        <i class="icon md-calendar" aria-hidden="true"></i>
                      </span>

                        <input type="text" class="input-sm form-control" name="start_date" placeholder="From date" id="fromdate" value="{{request()->start_date}}" required readonly />
                        <span class="input-group-addon">to</span>
                        <input type="text" class="input-sm form-control" name="end_date" placeholder="To date" id="todate" value="{{request()->end_date}}" required readonly />



                    </div>
                    </div>
                </div>
                <div class="col-md-4"><div class="form-group"><label for="amount">Amount</label><input type="number" class="form-control" id="amount" name="amount"  value="{{request()->amount}}"></div></div>
                    <div class="col-md-4"><div class="form-group"><label for="status">Approval Status</label><select name="status" id="status" style="width:100%;" class="form-control " >
                                <option value="">-Select Approval Status-</option>

                                    <option {{request()->status==1?"selected":""}} value="1">Approved</option>
                                <option {{request()->status===0?"selected":""}} value="0">Pending</option>
                                <option {{request()->status==2?"selected":""}} value="2">Rejected</option>

                            </select></div></div>
                    <div class="col-md-4"><div class="form-group"><label for="status">Payment Status</label><select name="disbursed" id="disbursed" style="width:100%;" class="form-control " >
                                <option  value="">-Select Payment Status-</option>

                                <option {{request()->disbursed==1?"selected":""}} value="1">Approved</option>
                                <option {{request()->disbursed===0?"selected":""}} value="0">Pending</option>

                            </select></div></div>
                <div class="col-md-12">
                    <div class="form-group ">
                        <input type="hidden" value="" id="excel" name="excel">
                        <input type="hidden" value="" id="excelall" name="excelall">

                        <button class="btn btn-primary " type="submit">Filter</button>
                        <a href="{{url('employee_reimbursements/index')}}" class="btn btn-warning ">Clear Filter</a>
                    </div>

                </div>
                </form>
                <div class="col-ms-12 col-xs-12 col-md-12">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title"> Employee Expense Reimbursements</h3>
                            <div class="panel-actions">
                                <button class="btn  btn-success  waves-effect" title="Export to Excel" id="exporttoexcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Export Current View</button>
                                <button class="btn  btn-success  waves-effect" title="Export to All Excel" id="exportalltoexcel"><i class="fa fa-file-excel-o" aria-hidden="true"> </i>&nbsp;Export All</button>


                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Expense Reimbursement Type</th>
                                        <th>Title</th>
                                        <th>Expense Date</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Approval Status</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @foreach($employee_reimbursements as $er)
                                            <td>{{$er->user?$er->user->name:""}}</td>
                                            <td>{{$er->employee_reimbursement_type?$er->employee_reimbursement_type->name:""}}</td>
                                            <td>{{$er->title}}</td>
                                            <td>{{date("F j, Y", strtotime($er->expense_date))}}</td>

                                            <td>{{round($er->amount,2)}}</td>
                                            <td>{{$er->description}}</td>
                                            <td><span class=" tag   {{$er->status==0?'tag-warning':($er->status==1?'tag-success':'tag-danger')}}">{{$er->status==0?'pending':($er->status==1?'approved':'rejected')}}</span></td>
                                            <td><span class=" tag   {{$er->disbursed==0?'tag-warning':($er->disbursed==1?'tag-success':'tag-danger')}}">{{$er->disbursed==0?'pending':($er->disbursed==1?'disbursed':'rejected')}}</span></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                                        @if($er->status==1)
                                                        <a data-id="{{ $er->id }}" style="cursor:pointer;"class="dropdown-item disburse" id="{{$er->id}}" onclick="disburse(this.id)"><i class="fa fa-money" aria-hidden="true"></i>&nbsp;Disburse Fund</a>
                                                        @endif
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
                @if (count($employee_reimbursements)>1)
                    {!! $employee_reimbursements->appends(Request::capture()->except('page'))->render() !!}
                @endif

            </div>
        </div>
    </div>

    <!-- End Page -->
    @include('employee_reimbursements.modals.disburse_expense')
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
            $(document).on('click','#exporttoexcel',function(event){
                event.preventDefault();
                $('#excel').val(true);
                $('#filterForm').submit();
            });
            $(document).on('click','#exportalltoexcel',function(event){
                event.preventDefault();
                $('#excelall').val(true);
                $('#filterForm').submit();
            });
            $('#DisburseEmployeeReimbursementForm').submit(function(e){
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
                        $('#DisburseEmployeeReimbursementModal').modal('toggle');
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
        function disburse(employee_reimbursement_id) {
            $(document).ready(function () {
                $('#disbursement_id').val(employee_reimbursement_id);
                $('#DisburseEmployeeReimbursementModal').modal();
            });

        }


    </script>
@endsection
