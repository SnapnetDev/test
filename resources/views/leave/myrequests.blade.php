@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet"/>
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
            <h1 class="page-title">{{__('Leave Request')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Leave Request')}}</li>
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
                <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
                    <div class="card card-shadow">
                        <div class="card-block bg-white p-20">
                            <button type="button" class="btn btn-floating btn-sm btn-danger">
                                <i class="fa fa-calendar-o"></i>
                            </button>
                            <span class="m-l-15 font-weight-400">SPILL OVER-{{date('Y')-1}}</span>
                            <div class="content-text text-xs-center m-b-0">
                                <i class="text-success icon wb-triangle-down font-size-20">
                                </i>
                                <span class="font-size-40 font-weight-100">

            {{$oldleaveleft>0? number_format($oldleaveleft):0}} Days

          </span>
                                <p class="blue-grey-400 font-weight-100 m-0">Spill Over days</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
                    <div class="card card-shadow" id="leave_bank">
                        <div class="card-block bg-white p-20">
                            <button type="button" class="btn btn-floating btn-sm btn-danger">
                                <i class="fa fa-calendar-o"></i>
                            </button>
                            <span class="m-l-15 font-weight-400">LEAVE BANK-{{date('Y')}}</span>
                            <div class="content-text text-xs-center m-b-0">
                                <i class="text-success icon wb-triangle-down font-size-20">
                                </i>
                                <span class="font-size-40 font-weight-100">

            {{$leavebank>0? number_format($leavebank):0}} Days

          </span>
                                <p class="blue-grey-400 font-weight-100 m-0">Annual Leave</p>
                            </div>
                        </div>
                    </div>
                    <div id="leaveBankContent" style="display:none;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Entitled Days</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $ln=1;
                            @endphp
                            @foreach($leave_info as $info)
                                <tr>
                                    <td>{{$ln}}</td>
                                    <td>{{$info['name']}}</td>
                                    <td>{{$info['entitled']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
                    <div class="card card-shadow" id="used_leave">
                        <div class="card-block bg-white p-20">
                            <button type="button" class="btn btn-floating btn-sm btn-danger">
                                <i class="fa fa-calendar-o"></i>
                            </button>
                            <span class="m-l-15 font-weight-400"> USED LEAVE -{{date('Y')}}</span>
                            <div class="content-text text-xs-center m-b-0">
                                <i class="text-success icon wb-triangle-down font-size-20">
                                </i>
                                <span class="font-size-40 font-weight-100">

            {{$used_days>0?number_format($used_days):0}} Days

          </span>
                                <p class="blue-grey-400 font-weight-100 m-0">Annual Leave</p>
                            </div>
                        </div>
                    </div>
                    <div id="usedLeaveContent" style="display:none;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Usage</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $ln=1;
                            @endphp
                            @foreach($leave_info as $info)
                                <tr>
                                    <td>{{$ln}}</td>
                                    <td>{{$info['name']}}</td>
                                    <td>{{$info['usage']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
                    <div class="card card-shadow" id="leave_balance">
                        <div class="card-block bg-white p-20">
                            <button type="button" class="btn btn-floating btn-sm btn-danger">
                                <i class="fa fa-calendar-o"></i>
                            </button>
                            <span class="m-l-15 font-weight-400">LEAVE BALANCE -{{date('Y')}}</span>
                            <div class="content-text text-xs-center m-b-0">
                                <i class="text-success icon wb-triangle-down font-size-20">
                                </i>
                                <span class="font-size-40 font-weight-100">

            {{$leaveleft>0?number_format($leaveleft):0}} Days

          </span>
                                <p class="blue-grey-400 font-weight-100 m-0">Annual Leave</p>
                            </div>
                        </div>
                    </div>
                    <div id="leaveBalanceContent" style="display:none;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $ln=1;
                            @endphp
                            @foreach($leave_info as $info)
                                <tr>
                                    <td>{{$ln}}</td>
                                    <td>{{$info['name']}}</td>
                                    <td>{{$info['balance']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
                    <div class="card card-shadow">
                        <div class="card-block bg-white p-20">
                            <button type="button" class="btn btn-floating btn-sm btn-success" data-toggle="modal"
                                    data-target="#requests">
                                <i class="fa fa-question"></i>
                            </button>
                            <span class="m-l-15 font-weight-400">REQUESTS</span>
                            <div class="content-text text-xs-center m-b-0">
                                <i class="text-danger icon wb-triangle-up font-size-20">
                                </i>
                                <span class="font-size-40 font-weight-100">{{count($leave_requests)}}</span>
                                <p class="blue-grey-400 font-weight-100 m-0">

                                    {{count($pending_leave_requests)}} Pending Approvals
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
                    <div class="card card-shadow" id="leave">
                        <div class="card-block bg-white p-20">
                            <button type="button" class="btn btn-floating btn-sm btn-primary">
                                <i class="fa fa-cubes"></i>
                            </button>
                            <span class="m-l-15 font-weight-400">Leave Type</span>
                            <div class="content-text text-xs-center m-b-0">
                                <i class="text-success icon wb-triangle-up font-size-20">
                                </i>
                                <span class="font-size-40 font-weight-100">{{count($leaves)+1}}</span>
                                <p class="blue-grey-400 font-weight-100 m-0">Leave Type</p>
                            </div>
                        </div>
                    </div>
                    <div id="leaveContent" style="display:none;">
                        <ul class="list-group list-group-dividered list-group-full">
                            <li class="list-group-item ">Annual Leave</li>
                            @foreach($leaves as $leave)
                                <li class="list-group-item ">{{$leave->name}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
                    <div class="card card-shadow">
                        <div class="card-block bg-white p-20" id="holiday">
                            <button type="button" class="btn btn-floating btn-sm btn-warning" data-toggle="modal"
                                    data-target="#holidays">
                                <i class="fa fa-lg fa-plane"></i>
                            </button>
                            <span class="m-l-15 font-weight-400">HOLIDAYS</span>
                            <div class="content-text text-xs-center m-b-0">
                                <i class="text-success icon wb-triangle-up font-size-20"></i>
                                <span class="font-size-40 font-weight-100">{{count($holidays)}} </span>
                                <p class="blue-grey-400 font-weight-100 m-0">Recognised Public Holidays</p>
                            </div>
                        </div>
                    </div>
                    <div id="holidayContent" style="display:none;">
                        <ul class="list-group list-group-dividered list-group-full">
                            @foreach($holidays as $holiday)
                                <li class="list-group-item ">{{$holiday->title}}:{{date("F j, Y",
                                    strtotime($holiday->date))}}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
                    <div class="card card-shadow">
                        <div class="card-block bg-white p-20" id="holiday">
                            <button type="button" class="btn btn-floating btn-sm btn-info" data-toggle="modal"
                                    data-target="#holidays">
                                <i class="fa fa-lg fa-calendar"></i>
                            </button>
                            <span class="m-l-15 font-weight-400">LEAVE PLAN-{{date('Y')}}</span>
                            <button type="button" class="btn btn-floating btn-sm btn-success pull-right"
                                    title="Add Leave Plan" data-toggle="modal" data-target="#addLeavePlanModal">
                                <i class="fa fa-lg fa-plus font-weight-100 font-size-40"></i>
                            </button>
                            @if(!isset($leave_plans))
                                <div class="content-text text-xs-center m-b-0">
                                    <i class="text-success icon wb-triangle-up font-size-20"></i>
                                    <button type="button" class="btn btn-floating btn-sm btn-success"
                                            data-toggle="modal" data-target="#leaveplan">
                                        <i class="fa fa-lg fa-plus font-weight-100 font-size-40"></i>
                                    </button>
                                    <p class="blue-grey-400 font-weight-100 m-0">Add Leave Plans</p>
                                </div>
                            @else
                                <div class="content-text text-xs-center m-b-0" id="leave_plan">
                                    <i class="text-success icon wb-triangle-up font-size-20"></i>
                                    <span class="font-size-40 font-weight-100">{{count($leave_plans)}} </span>

                                    <p class="blue-grey-400 font-weight-100 m-0">Hover to view annual leave plans</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div id="leavePlanContent" style="display:none;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Starts</th>
                                <th>Ends</th>
                                <th>Length</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $lsn=1;
                            @endphp
                            @foreach($leave_plans as $leave_plan)
                                <tr>
                                    <td>{{$lsn}}</td>
                                    <td>{{date("F j, Y", strtotime($leave_plan->start_date))}}</td>
                                    <td>{{date("F j, Y", strtotime($leave_plan->end_date))}}</td>
                                    <td>{{$leave_plan->length}}</td>
                                    <td><a href="#" class="deleteLeavePlan" title="Delete Leave Plan"
                                           id="{{$leave_plan->id}}"><i class="fa fa-trash text-danger"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <!-- End First Row -->
                {{-- second row --}}
                <div class="col-ms-12 col-xs-12 col-md-12">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">New Leave Request</h3>
                            <div class="panel-actions">
                                @if($lp->probationer_applies==1 && $user->status==0)
                                    <button class="btn btn-info" data-toggle="modal"
                                            data-target="#addLeaveRequestModal">New Leave Request
                                    </button>
                                @elseif($user->status==1)
                                    <button class="btn btn-info" data-toggle="modal"
                                            data-target="#addLeaveRequestModal">New Leave Request
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Starts</th>
                                        <th>Ends</th>
                                        <th>Priority</th>
                                        <th>Reason</th>
                                        <th>Leave length</th>
                                        <th>Approval Status</th>
                                        <th>With Pay</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @foreach($leave_requests as $leave_request)
                                            <td>{{$leave_request->leave_name}}</td>
                                            <td>{{date("F j, Y", strtotime($leave_request->start_date))}}</td>
                                            <td>{{date("F j, Y", strtotime($leave_request->end_date))}}</td>
                                            <td>
                                                <span class=" tag tag-outline  {{$leave_request->priority==0?'tag-success':($leave_request->priority==1?'tag-warning':'tag-danger')}}">{{$leave_request->priority==0?'normal':($leave_request->priority==1?'medium':'high')}}</span>
                                            </td>
                                            <td>{{$leave_request->reason}}</td>
                                            <td>{{$leave_request->length}}</td>
                                            <td>
                                                <span class=" tag   {{$leave_request->status==0?'tag-warning':($leave_request->status==1?'tag-success':'tag-danger')}}">{{$leave_request->status==0?'pending':($leave_request->status==1?'approved':'rejected')}}</span>
                                            </td>
                                            <td>{{$leave_request->paystatus==0?'No':'Yes'}}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                            id="exampleIconDropdown1"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                         role="menu">
                                                        <a data-id="{{ $leave_request->id }}" style="cursor:pointer;"
                                                           class="dropdown-item view-approval"
                                                           id="{{$leave_request->id}}"
                                                           onclick="viewRequestApproval(this.id)"><i class="fa fa-eye"
                                                                                                     aria-hidden="true"></i>&nbsp;View
                                                            Approval</a>
                                                        @if ($leave_request->status == 0)
                                                            <a data-leave-cancel="{{ $leave_request->id }}"
                                                               style="cursor:pointer;"
                                                               class="dropdown-item view-approval"><i
                                                                        class="fa fa-delete" aria-hidden="true"></i>&nbsp;Cancel
                                                                Leave</a>

                                                            <a data-toggle="modal" data-target="#editLeaveRequestModal"
                                                               data-leave-edit="{{ $leave_request->id }}"
                                                               style="cursor:pointer;"
                                                               class="dropdown-item view-approval"><i
                                                                        class="fa fa-delete" aria-hidden="true"></i>&nbsp;Edit
                                                                Leave Interval</a>
                                                        @endif
                                                        @if($leave_request->absence_doc!='')
                                                            <a style="cursor:pointer;" class="dropdown-item" id=""
                                                               href="{{url('leave/download?leave_request_id='.$leave_request->id)}}"><i
                                                                        class="fa fa-download" aria-hidden="true"></i>&nbsp;Download
                                                                Document</a>
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
    @include('leave.modals.addrequest')
    @include('leave.modals.editrequest')
    @include('leave.modals.add_leave_plan')
    {{-- Leave Request Details Modal --}}
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="leaveDetailsModal" aria-hidden="true"
         aria-labelledby="leaveDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Leave Request Details</h4>
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
    <script type="text/javascript"
            src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{ asset('global/vendor/webui-popover/jquery.webui-popover.min.js') }}"></script>

    <script type="text/javascript">


        (function ($) {

            $(function () {
                $('#dates').hide();

                $('.selecttwo').select2();
                $('#holiday').webuiPopover({
                    title: 'Holidays',
                    url: '#holidayContent',
                    placement: 'left',
                    trigger: 'hover'
                });
                $('#leave').webuiPopover({
                    title: 'Leave Types',
                    url: '#leaveContent',
                    placement: 'left',
                    trigger: 'hover'
                });
                $('#leave_plan').webuiPopover({
                    title: 'Leave Plans',
                    url: '#leavePlanContent',
                    placement: 'left',
                    trigger: 'hover',
                    width: 500
                });
                $('#leave_bank').webuiPopover({
                    title: 'Leave Bank',
                    url: '#leaveBankContent',
                    placement: 'top',
                    trigger: 'hover',
                    width: 500
                });
                $('#used_leave').webuiPopover({
                    title: 'Leave Usage',
                    url: '#usedLeaveContent',
                    placement: 'left',
                    trigger: 'hover',
                    width: 500
                });
                $('#leave_balance').webuiPopover({
                    title: 'Leave Balance',
                    url: '#leaveBalanceContent',
                    placement: 'left',
                    trigger: 'hover',
                    width: 500
                });
                ///module start///

                let check = 0;
                let vl = 0;

                function setLeaveLength() {
                    let leave_id = $('#abtype').val();
                    @if($oldleaveleft>0)
                        is_spillover = $('#is_spillover').val();
                    @else
                        is_spillover = 'no';
                    @endif

                    $.get('{{ url('/leave/get_entitled_leave_length') }}/', {leave_id: leave_id,is_spillover:is_spillover}, function (data) {
                        $('#leavelength').val(data.balance);
                        $('#paystatus').val(data.paystatus);

                        if (doValidate(data.balance).check) {
                            $('#leaveremaining').val(doValidate(data.balance).value);
                        }

                    });
                }

                function doValidate(v) {
                    vl = v || vl;
                    check = vl - $('#leave_days_requested').val();
                    if (check < 0) {

                        toastr.error('Your leave days cannot exceed your entitled days (' + $('#leave_days_requested').val() + ')');

                        //alert('Your leave days cannot exceed your entitled days (' + $('#leave_days_requested').val() + ')');
                    }
                    return {
                        check: check > 0,
                        value: check
                    };
                }


                function ajaxStart() {
                    toastr.info('Processing ...');

                }

                function ajaxStop() {
                    toastr.info('Done.');
                    document.getElementById("loader").style.display = "none";
                }


                function postForm($form, $api) {

                    let promise = {
                        success: function (cb, inValue) {
                            // cb(inValue);
                            console.log(this);
                            this._success = cb;
                            return promise;
                        },
                        before: function (cb) {
                            this._before = cb;
                            return promise;
                        },
                        _before: function () {
                            return true;
                        },
                        _success: function () {
                            //
                        },///
                        _error: function () {
                            //
                        },
                        error: function (cb, inError) {
                            // cb(inError);
                            this._error = cb;
                            return promise;
                        },
                        processSuccess: function (response) {
                            this._success(response);
                        },
                        processError: function (responseError) {
                            this._error(responseError);
                        },
                        processBefore: function (content) {
                            return this._before(content);
                        }
                    };

                    if (promise.processBefore({})) {

                        ajaxStart();

                        var form = $form;
                        var formdata = false;
                        if (window.FormData) {
                            formdata = new FormData(form[0]);
                        }
                        $.ajax({
                            url: $api,
                            data: formdata ? formdata : form.serialize(),
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'POST',
                            success: function (data, textStatus, jqXHR) {

                                // promise.success();
                                promise.processSuccess({
                                    data: data,
                                    textStatus: textStatus,
                                    jqXHR: jqXHR
                                });

                                ajaxStop();

                                // toastr.success("Changes saved successfully..",'Success');
                                // $('#addLeaveRequestModal').modal('toggle');

                                // location.reload();

                            },
                            error: function (data, textStatus, jqXHR) {

                                promise.processSuccess({
                                    data: data,
                                    textStatus: textStatus,
                                    jqXHR: jqXHR
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

                function selectionType() {

                }

                function initAddLeaveForm() {

                    postForm($('#addLeaveRequestForm'), "{{route('leave.store')}}")
                        .before(function () {
                            return doValidate().check;
                        })
                        .success(function (response) {
                            toastr.success("Changes saved successfully..", 'Success');
                            $('#addLeaveRequestModal').modal('toggle');
                            // location.reload();
                        })
                        .error(function (responseError) {
                            toastr.error('Something went wrong!');
                        });

                }

                function initEditLeaveForm() {

                    postForm($('#editLeaveRequestForm'), "{{route('leave.store')}}")
                        .before(function () {
                            return doValidate().check;
                        })
                        .success(function (response) {
                            toastr.success("Changes saved successfully..", 'Success');
                            $('#editLeaveRequestModal').modal('toggle');
                            location.reload();
                        })
                        .error(function (responseError) {
                            toastr.error('Something went wrong!');
                        });

                }

                function initAddLeavePlanForm() {

                    postForm($('#addLeavePlanForm'), "{{route('leave.store')}}")
                        .before(function () {
                            return doValidate().check;
                        })
                        .success(function (response) {
                            toastr.success("Changes saved successfully..", 'Success');
                            $('#addLeavePlanModal').modal('toggle');

                            location.reload();
                        })
                        .error(function (responseError) {
                            toastr.error('Something went wrong!');
                        });

                }


                // $('.input-daterange').datepicker({
                //   autoclose: true,
                //   format:'yyyy-mm-dd'
                // });
                $('.input-daterange').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: new Date,
                    autoclose: true,
                    closeOnDateSelect: true,
                    daysOfWeekDisabled: [@if($lp->includes_weekend==0)0, 6 @endif],
                    datesDisabled: [@if($lp->includes_holiday==0) @foreach($holidays as $holiday)"{{date('Y-m-d',strtotime($holiday->date))}}",@endforeach @endif]
                }).datepicker("setDate", 'now');

                $('#selection ').datepicker({
                    format: 'yyyy-mm-dd',
                    multidate: true,
                    todayHighlight: true,
                    daysOfWeekDisabled: [@if($lp->includes_weekend==0)0, 6 @endif],
                    clearBtn: true,
                    datesDisabled: [@if($lp->includes_holiday==0) @foreach($holidays as $holiday)"{{date('Y-m-d',strtotime($holiday->date))}}",@endforeach @endif]
                });


                $(document).on('submit', '#addLeaveRequestForm', function (event) {
                    event.preventDefault();
                    initAddLeaveForm();
                });
                $(document).on('submit', '#addLeavePlanForm', function (event) {
                    event.preventDefault();
                    initAddLeavePlanForm();
                });


                function viewRequestApproval(leave_request_id) {
                    $(document).ready(function () {
                        $("#detailLoader").load('{{ url('/leave/getdetails') }}?leave_request_id=' + leave_request_id);
                        $('#leaveDetailsModal').modal();
                    });

                }

                window.viewRequestApproval = viewRequestApproval;  //Export to global windows object.
                $('#days_selection_type').on('change', function () {
                    console.log($(this).val());
                    if ($(this).val() == 'range') {
                        $('#range').show();
                        $('#dates').hide();
                        $('#leave_days_requested').val('');

                    }
                    if ($(this).val() == 'dates') {
                        $('#dates').show();
                        $('#range').hide();
                        $('#leave_days_requested').val('');
                    }

                });

                $('#abtype').on('change', function () {
                    setLeaveLength();

                });

                $('#fromdate').on('change', function () {
                    fromdate = $('#fromdate').val();
                    todate = $('#todate').val();

                    $.get('{{ url('/leave/get_leave_requested_days') }}/', {
                        fromdate: fromdate,
                        todate: todate,
                    }, function (data) {
                        $('#leave_days_requested').val(data);
                    });
                    setLeaveLength();
                });


                $('#todate').on('change', function () {
                    fromdate = $('#fromdate').val();
                    todate = $('#todate').val();

                    $.get('{{ url('/leave/get_leave_requested_days') }}/', {
                        fromdate: fromdate,
                        todate: todate,
                        type: 'range'
                    }, function (data) {
                        $('#leave_days_requested').val(data);
                    });
                    setLeaveLength()
                });

                $('#selection').on('change', function () {
                    selection = $(this).val();

                    $.get('{{ url('/leave/get_leave_requested_days') }}/', {
                        selection: selection,
                        type: 'dates'
                    }, function (data) {
                        $('#leave_days_requested').val(data);
                    });
                    setLeaveLength();
                });


                $('#emps').select2({
                    placeholder: "Employee Name",
                    multiple: false,
                    id: function (bond) {
                        return bond._id;
                    },
                    ajax: {
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        url: function (params) {
                            return '{{url('users')}}/search';
                        }
                    }

                });


                ///module stop///

            });

        })(jQuery);

        ////End - JQuery ...

    </script>
    <script>


        function cancelLeave(id) {
            //http://127.0.0.1:8000/leave/myrequests
            $(function () {
                $.ajax({
                    url: '{{ url('leave/cancel_leave') }}?id=' + id,
                    type: 'GET',
                    success: function (response) {
                        toastr.info('Leave Cancellation', response.message);
                        setTimeout(function () {
                            location.reload();
                        }, 5000);
                    }
                });
            });
        }

        $(function () {
            $('[data-leave-cancel]').each(function () {
                var id = $(this).data('leave-cancel');
                $(this).on('click', function () {
                    //cancel_leave
                    if (confirm('Do you want to confirm this removal?')) {
                        cancelLeave(id);
                    }
                    return false;
                });
            });
        });


    </script>
    <script type="text/javascript">

        function datePicker() {
            $('.period_daterange').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        }

        $(document).ready(function () {

            datePicker();

            // var compcont = $('#plancont');

            $('#addComponent').on('click', function () {
                $('.datepickerDiv').clone().appendTo('#plancont').removeClass('datepickerDiv');
                datePicker()
            });


            $(document).on('click', ".remComponent", function () {
                if ($('#plancont').text() == '') {
                    return toastr.info('You cannot remove the only component');
                }
                $(this).parents('li').remove();
            });

            // $('.period_start_date').change(function() {
            // $(this).nextAll('.period_end_date:first').val( $(this).val());
            // return false;
            // });
            $(document).on('click', ".deleteLeavePlan", function () {
                leave_id = $(this).attr('id');
                toastr.info('Deleting.....');
                $.get('{{ url('/leave/delete_leave_plan') }}/', {leave_id: leave_id}, function (data) {
                    if (data == 'success') {
                        toastr.success('Leave Plan deleted successfully');
                    } else {
                        toastr.info('Leave Plan could not be deleted');
                    }
                });

            });


            //           $('.leave_period-daterange').datepicker({
            //   autoclose: true,
            //   format:'yyyy-mm-dd'
            // });
        });

    </script>
@endsection
