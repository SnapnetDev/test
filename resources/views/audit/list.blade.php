@extends('layouts.master')
@section('stylesheets')

    <link rel="stylesheet" href="{{ asset('assets/examples/css/apps/mailbox.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
@endsection

@section('content')
    <div>
        <div class="page bg-white">
            <!-- Mailbox Sidebar -->
            <div class="page-aside">
                <div class="page-aside-switch">
                    <i class="icon md-chevron-left" aria-hidden="true"></i>
                    <i class="icon md-chevron-right" aria-hidden="true"></i>
                </div>
                <div class="page-aside-inner page-aside-scroll">
                    <div data-role="container">
                        <div data-role="content">
                            <div class="page-aside-section">
                                <div class="list-group">
                                    <a class="list-group-item" href="{{url('audits/index')}}"><i class="icon md-accounts-add"
                                                                                                 aria-hidden="true"></i>Profile Changes</a>
                                    <a class="list-group-item " href="{{url('audits/view_login_activity')}}"><i class="icon md-lock-open"
                                                                                                                aria-hidden="true"></i>Login
                                        Activity</a>
                                    <a class="list-group-item " href="{{url('audits/view_payroll_activity')}}"><i class="icon md-money-box"
                                                                                                                  aria-hidden="true"></i>Payroll
                                        Activity</a>
                                    <a class="list-group-item " href="{{url('audits/view_salary_component_activity')}}"><i
                                                class="icon md-money-box" aria-hidden="true"></i>Salary Component Activity</a>
                                    <a class="list-group-item " href="{{url('audits/view_leave_request_activity')}}"><i
                                                class="icon md-money-box" aria-hidden="true"></i>Leave Request Activity</a>

                                    <a class="list-group-item " href="{{url('audits/view_leave_request_recall_activity')}}"><i
                                                class="icon md-money-box" aria-hidden="true"></i>Leave Recall Activity</a>

                                    <a class="list-group-item " href="{{url('audits/view_leave_request_adjustment_activity')}}"><i
                                                class="icon md-money-box" aria-hidden="true"></i>Leave Adjustment Activity</a>

                                    <a class="list-group-item " href="{{url('audits/view_bsc_activity')}}"><i
                                                class="icon md-money-box" aria-hidden="true"></i>Balance Score Card</a>

                                    <a class="list-group-item " href="{{url('audits/view_bsc_evaluation_activity')}}"><i
                                                class="icon md-money-box" aria-hidden="true"></i>BSC Evaluation Activity</a>

                                    <a class="list-group-item " href="{{url('audits/view_specific_salary_component_activity')}}"><i
                                                class="icon md-money-box" aria-hidden="true"></i>Specific Salary Component</a>

                                    <a class="list-group-item " href="{{url('audits/view_pace_salary_activity')}}"><i
                                                class="icon md-money-box" aria-hidden="true"></i>Pace Salary Activities</a>


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Mailbox Content -->
            <div class="page-main" style="min-height:700px;">
                <!-- Mailbox Header -->
                <div class="page-header">
                    <h1 class="page-title"> {{isset($type)?ucfirst($type):''}} Audit Log</h1>
                    <div class="page-header-actions">

                        <form style="width: 30%;" class="pull-right" id="filterForm">


                            <div class="input-daterange input-group" id="datepicker">
                    <span class="input-group-addon">
                        <i class="icon md-calendar" aria-hidden="true"></i>
                      </span>
                                <input type="hidden" value="" id="excel" name="excel">
                                <input type="hidden" value="" id="excelall" name="excelall">
                                <input type="text" class="input-sm form-control" name="start_date" placeholder="From date" id="fromdate"
                                       value="{{request()->start_date}}" required readonly/>
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" name="end_date" placeholder="To date" id="todate"
                                       value="{{request()->end_date}}" required readonly/>


                                <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit"><i class="icon md-search"></i>Search</button>

                      </span>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- Mailbox Content -->
                <div id="mailContent" class="page-content page-content-table" data-plugin="asSelectable">
                    <!-- Actions -->
                    <div class="page-content-actions">

                        <div class="actions-main">
                            <button class="btn  btn-success  waves-effect" title="Export to Excel" id="exporttoexcel"><i
                                        class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Export Current View
                            </button>
                            <button class="btn  btn-success  waves-effect" title="Export to All Excel" id="exportalltoexcel"><i
                                        class="fa fa-file-excel-o" aria-hidden="true"> </i>&nbsp;Export All
                            </button>


                        </div>

                    </div>
                @if(count($activities)>=1 && $setting->value==1)
                    <!-- Mailbox -->
                        <table id="mailboxTable" class="table" data-plugin="animateList" data-animate="fade"
                               data-child="tr">
                            <tbody>
                            @foreach($activities as $activity)
                                @if($activity->subject_type=='App\User')
                                    <tr id="mid_1" data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=user'}}"
                                        data-toggle="slidePanel" style="">
                                @elseif($activity->subject_type=='App\Payroll')
                                    <tr id="mid_1" data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=payroll'}}"
                                        data-toggle="slidePanel" style="">
                                @elseif($activity->subject_type=='App\SalaryComponent')
                                    <tr id="mid_1"
                                        data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=salary_component'}}"
                                        data-toggle="slidePanel" style="">
                                @elseif($activity->subject_type=='App\PayrollPolicy')
                                    <tr id="mid_1"
                                        data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=payroll_policy'}}"
                                        data-toggle="slidePanel" style="">
                                @elseif($activity->log_name=='leaveRequest')
                                    <tr id="mid_1"
                                        data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=leave_request'}}"
                                        data-toggle="slidePanel" style="">

                                @elseif($activity->subject_type=='App\LeaveRequestRecall')
                                    <tr id="mid_1"
                                        data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=leave_recall'}}"
                                        data-toggle="slidePanel" style="">

                                @elseif($activity->subject_type=='App\LeaveRequestAdjustment')
                                    <tr id="mid_1"
                                        data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=leave_adjustment'}}"
                                        data-toggle="slidePanel" style="">


                                @elseif($activity->log_name=='bsc')
                                    <tr id="mid_1"
                                        data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=bsc'}}"
                                        data-toggle="slidePanel" style="">

                                @elseif($activity->log_name=='bscEvaluation')
                                    <tr id="mid_1"
                                        data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=bscEval'}}"
                                        data-toggle="slidePanel" style="">

                                @elseif($activity->log_name=='spsc')
                                    <tr id="mid_1"
                                        data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=spsc'}}"
                                        data-toggle="slidePanel" style="">

                                @elseif($activity->log_name=='paceSalary')
                                    <tr id="mid_1"
                                        data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=paceSalary'}}"
                                        data-toggle="slidePanel" style="">


                                        @endif
                                        <td class="cell-60">
                <span class="checkbox-custom checkbox-primary checkbox-lg">
                  <input type="checkbox" class="mailbox-checkbox selectable-item" id="mail_mid_1"
                  />
                  <label for="mail_mid_1"></label>
                </span>
                                        </td>

                                        <td class="cell-60 responsive-hide ">
                                            <a class="avatar" href="javascript:void(0)">
                                                <i class="icon md-search-replace  "></i>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="content">
                                                @if($activity->subject_type=='App\User')
                                                    <div class="title">{{$activity->causer?$activity->causer->name:''}} made changes
                                                        to {{$activity->subject?$activity->subject->name:''}}  </div>
                                                @elseif($activity->subject_type=='App\Payroll')
                                                    <div class="title">Payroll
                                                        was {{$activity->description}} {{$activity->causer?'by '.$activity->causer->name:''}}</div>
                                                @elseif($activity->subject_type=='App\Payroll')
                                                    <div class="title">{{$activity->subject?$activity->subject->name:''}} Salary Component
                                                        was {{$activity->description}} {{$activity->causer?'by '.$activity->causer->name:''}}</div>

                                                    <!--     Leave Request Activity Log       -->
                                                @elseif($activity->subject_type=='App\LeaveRequest'
                                                 && $activity->description == 'created' || $activity->description == 'deleted')
                                                    <div class="title"> Leave Request
                                                        was {{$activity->description}} {{$activity->causer?'by '.$activity->causer->name:''}}</div>

                                                @elseif($activity->log_name == 'leaveRequest' &&
                                                $activity->subject_type=='App\LeaveRequest' && $activity->description == 'updated')
                                                    <div class="title">{{$activity->causer?$activity->causer->name:''}} made changes
                                                        to {{($activity->subject)?$activity->subject->user->name:''}}</div>

                                                    <!--   Leave Approval Activity Log         -->
                                                @elseif($activity->log_name == 'leaveRequest'
                                                && $activity->subject_type=='App\LeaveApproval')
                                                    <div class="title">{{$activity->causer?$activity->causer->name:''}} has approved
                                                        {{$activity->subject?$activity->subject->leave_request->user->name.' Leave
                                                        Request': ''}}</div>

                                                    <!-- Recall Activity Log -->
                                                @elseif($activity->subject_type=='App\LeaveRequestRecall')
                                                    <div class="title">{{$activity->causer?$activity->causer->name:''}} initiated a
                                                        recall on
                                                        {{$activity->subject?$activity->subject->leave_request->user->name.' Leave
                                                        Request': ''}}</div>

                                                    <!--  Leave Request Adjustment  -->
                                                @elseif($activity->subject_type=='App\LeaveRequestAdjustment')
                                                    <div class="title">{{$activity->causer?$activity->causer->name:''}} made
                                                        adjustments on
                                                        {{$activity->subject?$activity->subject->leave_request->user->name.' Leave
                                                        Request': ''}}</div>

                                                    <!-- Balance Sore Card   -->
                                                @elseif($activity->log_name=='bsc' && $activity->description == 'created')
                                                    <div class="title">{{$activity->causer->name}} Added
                                                        {{$activity->subject->name}} </div>

                                                @elseif($activity->log_name=='bsc' && $activity->description == 'updated')
                                                    <div class="title">{{$activity->causer->name}} made changes
                                                        to {{$activity->subject->name}} </div>

                                                    <!-- Balance Sore Card Evaluation  -->
                                                @elseif($activity->log_name=='bscEvaluation' && $activity->description == 'created')
                                                    <div class="title">{{$activity->causer->name}} Setup Evaluation
                                                        for {{$activity->subject->user->name}} </div>

                                                @elseif($activity->log_name=='bscEvaluation' && $activity->description == 'updated')
                                                    <div class="title">{{$activity->causer->name}} made changes
                                                        to {{$activity->subject->name}} </div>

                                                    <!--     Specific Salary Component  -->
                                                @elseif($activity->log_name=='spsc' && $activity->description == 'created' ||
                                                $activity->description
                                                 == 'deleted')
                                                    <div class="title">{{$activity->causer->name}} {{$activity->description}} a Specific
                                                        Salary Component
                                                    </div>
                                                @elseif($activity->log_name=='spsc' && $activity->description == 'updated')
                                                    <div class="title">{{$activity->causer?$activity->causer->name:''}} made changes
                                                        to {{$activity->subject?$activity->subject->name:''}}</div>


                                                @endif

                                                <div class="abstract"></div>
                                            </div>
                                        </td>
                                        <td class="cell-30 responsive-hide">
                                        </td>
                                        <td class="cell-130">
                                            <div class="time">{{$activity->created_at->diffForHumans()}}</div>
                                            {{--  <div class="identity"><i class="md-circle red-600" aria-hidden="true"></i>Work</div> --}}
                                        </td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    @else
                        <h3 style="margin-left:30%"> No Changes Found !!! </h3>
                    @endif
                </div>

            @if (count($activities)>1 && $setting->value==1)
                {!! $activities->appends(Request::capture()->except('page'))->render() !!}

            @endif
            <!-- End Add Label Form -->
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('assets/js/App/Mailbox.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <!-- <script src="{{ asset('assets/examples/js/apps/mailbox.js')}}"></script> -->
    <script>
        $(document).ready(function () {
            $('.input-daterange').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $(document).on('click', '#exporttoexcel', function () {

                $('#excel').val(true);
                $('#filterForm').submit();
                $('#excel').val('');
            });
            $(document).on('click', '#exportalltoexcel', function () {

                $('#excelall').val(true);
                $('#filterForm').submit();
                $('#excelall').val('');

            });
        });
    </script>
@endsection