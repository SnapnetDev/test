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
                                    <a class="list-group-item" href="{{url('audits/index')}}"><i class="icon md-accounts-add" aria-hidden="true"></i>Profile Changes</a>
                                    <a class="list-group-item " href="{{url('audits/view_login_activity')}}"><i class="icon md-lock-open" aria-hidden="true"></i>Login Activity</a>
                                    <a class="list-group-item " href="{{url('audits/view_payroll_activity')}}"><i class="icon md-money-box" aria-hidden="true"></i>Payroll Activity</a>
                                    <a class="list-group-item " href="{{url('audits/view_salary_component_activity')}}"><i class="icon md-money-box" aria-hidden="true"></i>Salary Component Activity</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Mailbox Content -->
            <div class="page-main">
                <!-- Mailbox Header -->
                <div class="page-header">
                    <h1 class="page-title"> {{isset($type)?ucfirst($type):''}} Audit Log</h1>
                    <div class="page-header-actions">

                        <form style="width: 30%;" class="pull-right">

                                <select name="user_id" id="" class="form-control" style="text-align: center;">
                                    <option value="0">Select Employee</option>
                                </select>


                            <div class="input-daterange input-group" id="datepicker">
                    <span class="input-group-addon">
                        <i class="icon md-calendar" aria-hidden="true"></i>
                      </span>

                                    <input type="text" class="input-sm form-control" name="start_date" placeholder="From date" id="fromdate" value="{{request()->start_date}}" required readonly />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="input-sm form-control" name="end_date" placeholder="To date" id="todate" value="{{request()->end_date}}" required readonly />


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
            <span class="checkbox-custom checkbox-primary checkbox-lg inline-block vertical-align-bottom">
              <input type="checkbox" class="mailbox-checkbox selectable-all" id="select_all"
              />
              <label for="select_all"></label>
            </span>


                        </div>

                    </div>
                @if(count($activities)>1)
                    <!-- Mailbox -->
                        <table id="mailboxTable" class="table" data-plugin="animateList" data-animate="fade"
                               data-child="tr">
                            <tbody>
                            @foreach($activities as $activity)
                                @if($activity->subject_type=='App\User')
                                    <tr id="mid_1" data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=user'}}" data-toggle="slidePanel" style="">
                                @elseif($activity->subject_type=='App\Payroll')
                                    <tr id="mid_1" data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=payroll'}}" data-toggle="slidePanel" style="">
                                @elseif($activity->subject_type=='App\SalaryComponent')
                                    <tr id="mid_1" data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=salary_component'}}" data-toggle="slidePanel" style="">
                                @elseif($activity->subject_type=='App\PayrollPolicy')
                                    <tr id="mid_1" data-url="{{url('audits/view_activity').'?activity_id='.$activity->id.'&type=payroll_policy'}}" data-toggle="slidePanel" style="">
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
                                                <i class="icon md-search-replace  " ></i>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="content">
                                                @if($activity->subject_type=='App\User')
                                                <div class="title">{{$activity->causer?$activity->causer->name:''}} made changes to {{$activity->subject?$activity->subject->name:''}}  </div>
                                                @elseif($activity->subject_type=='App\Payroll')
                                                    <div class="title">Payroll was {{$activity->description}} {{$activity->causer?'by '.$activity->causer->name:''}}</div>
                                                @elseif($activity->subject_type=='App\Payroll')
                                                    <div class="title">{{$activity->subject?$activity->subject->name:''}} Salary Component was {{$activity->description}} {{$activity->causer?'by '.$activity->causer->name:''}}</div>
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
            @if (request()->pagi!='all'&& count($activities)>1)
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
        $(document).ready(function() {
            $('.input-daterange').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd'
            });
        });
    </script>
@endsection
