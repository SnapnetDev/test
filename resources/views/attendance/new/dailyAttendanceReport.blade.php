@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/jt-timepicker/jquery-timepicker.css') }}">
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Daily Time and Attendance Report for ')}}  {{ $date->format('d M, Y') }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Daily Time and Attendance Report')}}</li>
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
            <div class="row">
                <div class="col-lg-8"></div>
                <div class="col-lg-4">
                    <form method="GET" action="{{route("daily.attendance.report")}}" style="text-align: right">
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon fa fa-calendar"
                                                                   aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="startdate"
                                       data-plugin="datepicker" name="date" autocomplete="off"
                                       placeholder="{{ $date->format('d M, Y') }}" value="{{ $date->format('m/d/Y') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <br>
            <div class="col-lg-3 col-xs-12">
                <!-- Card -->
                <div class="card card-block p-30" onclick="viewEarly()">
                    <div class="counter counter-md text-xs-left">
                        <div class="counter-label text-uppercase m-b-5"><b>{{__('Early Employee(s)')}}</b>
                        </div>
                        <div class="counter-number-group m-b-10">
                            <span class="counter-number">{{$earlys}}</span>
                        </div>

                    </div>
                </div>
                <!-- End Card -->
            </div>
            <div class="col-lg-3 col-xs-12" onclick="viewLate()">
                <!-- Card -->
                <div class="card card-block p-30">
                    <div class="counter counter-md text-xs-left">
                        <div class="counter-label text-uppercase m-b-5"><b>{{__('Late Employee(s)')}}</b>
                        </div>
                        <div class="counter-number-group m-b-10">
                            <span class="counter-number">{{$lates}}</span>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
            <div class="col-lg-3 col-xs-12">
                <!-- Card -->
                <div class="card card-block p-30">
                    <div class="counter counter-md text-xs-left">
                        <div class="counter-label text-uppercase m-b-5"><b>{{__('Off Day Employee(s)')}}</b></div>
                        <div class="counter-number-group m-b-10">
                            <span class="counter-number">{{$offs}}</span>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
            <div class="col-lg-3 col-xs-12">
                <!-- Card -->
                <div class="card card-block p-30">
                    <div class="counter counter-md text-xs-left">
                        <div class="counter-label text-uppercase m-b-5"><b>{{__('Absent Employee(s)')}}</b></div>
                        <div class="counter-number-group m-b-10">
                            <span class="counter-number">{{$absentees}}</span>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>


            <div class="col-md-12 col-xs-12 col-md-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Attendance Report for {{ $date->format('d M, Y') }}</h3>
                        <div class="panel-actions">
                            <button class="btn btn-info">
                                <a style="text-decoration: none; color: white"  href='{{ route('daily.attendance.report',['date'=>$date->format('m/d/Y'),'type'=>'excel']) }}'>
                                    Download Report</a>
                            </button>
                            <button class="btn btn-info">
                                <a style="text-decoration: none; color: white"  href='{{ route('manual.attendance',['date'=>$date->format('m/d/Y')]) }}'>
                                    Manual Attendance</a>
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                <thead>
                                <tr>
                                    <th>EMPID</th>
                                    <th>{{__('NAME')}}</th>
                                    <th>{{__('CLOCK IN')}}</th>
                                    <th>{{__('SHIFT STARTS')}}</th>
                                    <th>{{__('SHIFT ENDS')}}</th>
                                    <th>{{__('CLOCK OUT')}}</th>
                                    <th>{{__('HOURS WORKED')}}</th>
                                    <th>{{__('OVERTIME')}}</th>
                                    <th>{{__('SHIFT')}}</th>
                                    <th>{{__('STATUS')}}</th>
                                    <th>{{__('ACTION')}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(count($attendances)>0)
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td><a style="text-decoration: none;" href="{{ route('attendance.staff',$attendance->user->id) }}">{{$attendance->user->emp_num}}</a></td>
                                            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$attendance->user->id) }}">{{$attendance->user->name}}</a></td>
                                            <td>
                                                <span class="text text-success"><b>{{$attendance->first_clockin}}</b></span>
                                            </td>
                                            <td>{{$attendance->shift_start}}</td>
                                            <td>{{$attendance->shift_end}}</td>
                                            <td>{{$attendance->last_clockout}}</td>
                                            <td>{{$attendance->hours_worked}}</td>
                                            <td>
                                                @if(isset($attendance->attendance->workflow_status))<span style="cursor: pointer"
                                                   data-content="@foreach($attendance->attendance->workflow_details as $detail) Stage {{ $detail['position'] }}: {{ $detail['status'] }}@endforeach"
                                                   data-toggle="popover" data-original-title="Approval Details"
                                                   tabindex="0" title="">{{$attendance->overtime}}</span>
                                                @endif
                                            </td>

                                            <td>{{$attendance->shift_name}}</td>
                                            <td>
                                                <span class="tag {{$attendance->status=='early'?'tag-success':($attendance->status=='off'?'tag-primary':'tag-danger')}}">
                                                    {{ $attendance->status=='off'? 'Off Day' : $attendance->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-info">
                                                    <a style="cursor:pointer;" id="{{$attendance->attendance_id}}"
                                                       onclick="viewMore(this.id)"><i class="fa fa-eye"
                                                                                      aria-hidden="true"></i>
                                                        &nbsp;View More</a>
                                                </button>
                                                @if(isset($attendance->attendance->workflow_status))
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                                                        Approval
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                                        @if($attendance->attendance->workflow_status=='pending' && in_array(Auth::id(),collect($attendance->attendance->workflow_details)->where('status','pending')->first()['users'] ))
                                                            <a onclick="approve({{$attendance->id}})" style="cursor:pointer; text-decoration: none;" class="dropdown-item" href="#">&nbsp;Approve</a>
                                                            <a onclick="decline({{$attendance->id}})" style="cursor:pointer; text-decoration: none;" class="dropdown-item" href="#">&nbsp;Decline</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td  colspan="11" style="text-align: center">
                                            <b style="font-size:20px;"
                                               class="text-success"> {{__('No Attendance Report For Today Yet')}}</b>
                                        </td>

                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- End Page -->
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="attendanceDetailsModal" aria-hidden="true"
         aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Clock In History</h4>
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


    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="earlyDetailsModal" aria-hidden="true"
         aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Early Staff</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>EMPID</th>
                                    <th>{{__('NAME')}}</th>
                                    <th>{{__('CLOCK IN')}}</th>
                                    <th>{{__('SHIFT STARTS')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($attendances)>0)
                                    @foreach($attendances->where('status','early') as $attendance)
                                        <tr>
                                            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$attendance->user->id) }}">{{$attendance->user->emp_num}}</a></td>
                                            <td><a  style="text-decoration: none;" href="{{ route('attendance.staff',$attendance->user->id) }}">{{$attendance->user->name}}</a></td>
                                            <td>{{$attendance->first_clockin}}</td>
                                            <td>{{$attendance->shift_start}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" style="text-align: center">
                                            <b style="font-size:20px;"
                                               class="text-success"> {{__('No Attendance Report For Today Yet')}}</b>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
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

    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="lateDetailsModal" aria-hidden="true"
         aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Late Staff</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>EMPID</th>
                                    <th>{{__('NAME')}}</th>
                                    <th>{{__('CLOCK IN')}}</th>
                                    <th>{{__('SHIFT STARTS')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($attendances)>0)
                                    @foreach($attendances->where('status','late') as $attendance)
                                        <tr>
                                            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$attendance->user->id) }}">{{$attendance->user->emp_num}}</a></td>
                                            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$attendance->user->id) }}">{{$attendance->user->name}}</a></td>
                                            <td>{{$attendance->first_clockin}}</td>
                                            <td>{{$attendance->shift_start}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" style="text-align: center">
                                            <b style="font-size:20px;"
                                               class="text-success"> {{__('No Attendance Report For Today Yet')}}</b>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
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
    <script type="text/javascript">
        function approve(id){
            alertify.confirm('Are you sure you want to Approve this Attendance Overtime', function () {
                alertify.success('Processing this request. Please wait...');
                sendAjax(id,1)
            }, function () {
                alertify.error('Cancelled')
            })
        }
        function decline(id){
            alertify.confirm('Are you sure you want to Decline this Attendance Overtime', function () {
                alertify.success('Processing this request. Please wait...');
                sendAjax(id,2)
            }, function () {
                alertify.error('Cancelled')
            })
        }

        function sendAjax(id,stat){
            $.ajax({
                url: '{{url('attendance/overtime/approval')}}/'+id+'?status='+stat,
                type: 'GET',
                success: function (data, textStatus, jqXHR) {
                    if (data==='approved' || data==='declined' || data==='approved stage'){
                        alertify.success('Successful');
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                    }
                    else{
                        alertify.error('You do not have permission');
                    }
                },
                error: function (data, textStatus, jqXHR) {
                    alertify.error('Something went wrong')
                }
            });
        }

        function viewMore(attendance_id) {
            // $.get('{{ url('/attendance/getdetails') }}/'+attendance_id,function(data){
            $("#detailLoader").load('{{ url('/attendance/getdetails') }}/' + attendance_id);
            $('#attendanceDetailsModal').modal();
            // });
        }

        function viewEarly() {
            $('#earlyDetailsModal').modal();
            // });
        }

        function viewLate() {
            $('#lateDetailsModal').modal();
            // });
        }

    </script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('global/vendor/jt-timepicker/jquery.timepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/datepair.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/jquery.datepair.min.js')}}"></script>
@endsection