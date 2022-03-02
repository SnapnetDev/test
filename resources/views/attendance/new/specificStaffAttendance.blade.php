@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/jt-timepicker/jquery-timepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{ $staff->name }}{{__(' Staff Attendance Report')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{ $staff->name }}{{__(' Attendance Report')}}</li>
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
                <div class="col-lg-7"></div>
                <div class="col-lg-5">
                    <form method="GET" action="{{route('attendance.staff',$staff->id)}}" style="text-align: right">
                        <div class="col-md-5"  >
                            <div class="input-group">
                                <span class="input-group-addon">From <i class="icon fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="startdate" data-plugin="datepicker" name="from" autocomplete="off" value="{{$start}}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group"><span class="input-group-addon">To <i class="icon fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="enddate" data-plugin="datepicker" name="to"  autocomplete="off" value="{{$end}}">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <button title="Search" class="btn btn-success btn-sm" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <br>

            <div class="col-md-12 col-xs-12 col-md-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ $staff->name }} Attendance Report from {{ $start }} to {{ $end }}</h3>
                        <div class="panel-actions">
                            <a href="{{ route('attendance.staff',['user'=>$user->id,'type'=>'excel','from'=>$start,'to'=>$end]) }}">
                                <button class="btn btn-info" data-toggle="modal" data-target="#addLeaveRequestModal">Download Excel Report</button>
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                <thead>
                                <tr>
                                    <th class="hidden-sm-down">{{__('DATE')}}</th>
                                    <th class="hidden-sm-down">{{__('CLOCK IN')}}</th>
                                    <th class="hidden-sm-down">{{__('SHIFT STARTS')}}</th>
                                    <th class="hidden-sm-down">{{__('SHIFT ENDS')}}</th>
                                    <th class="hidden-sm-down">{{__('CLOCK OUT')}}</th>
                                    <th class="hidden-sm-down">{{__('HOURS WORKED')}}</th>
                                    <th class="hidden-sm-down">{{__('OVERTIME')}}</th>
                                    <th class="hidden-sm-down">{{__('SHIFT')}}</th>
                                    <th class="hidden-sm-down">{{__('STATUS')}}</th>
                                    <th class="hidden-sm-down">{{__('ACTION')}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(count($attendances)>0)
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td>{{$attendance->date}}</td>
                                            <td><span class="text text-success"><b>{{$attendance->first_clockin}}</b></span></td>
                                            <td>{{$attendance->shift_start}}</td>
                                            <td>{{$attendance->shift_end}}</td>
                                            <td>{{$attendance->last_clockout}}</td>
                                            <td>{{$attendance->hours_worked}}</td>
                                            <td>{{$attendance->overtime}}</td>
                                            <td>{{$attendance->shift_name}}</td>
                                            <td><span class="tag {{$attendance->status=='early'?'tag-success':'tag-danger'}}">{{ $attendance->status }}</span></td>
                                            <td>
                                                <button class="btn btn-info">
                                                    <a style="cursor:pointer;" id="{{$attendance->attendance_id}}"
                                                       onclick="viewMore(this.id)"><i class="fa fa-eye"
                                                                                      aria-hidden="true"></i>
                                                        &nbsp;View More</a>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10" style="text-align: center">
                                            <b style="font-size:20px;"
                                               class="text-success"> {{__('No Attendance Report For This Period')}}</b>
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

    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="attendanceDetailsModal" aria-hidden="true" aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog " >
            <div class="modal-content">
                <div class="modal-header" >
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
@endsection
        @section('scripts')
            <script type="text/javascript">
                function datesearch(type = 0) {

                    console.log("Hello");
                    startdate = $('#startdate').val();
                    starttime = $('#starttime').val();
                    enddate = $('#enddate').val();
                    endtime = $('#endtime').val();
                    empname = $('#q').val();

                    if (empname != "") {
                        addionalsearch = "&q=" + empname;
                    } else {
                        addionalsearch = "";
                    }
                    if (startdate == "" || starttime == "" || enddate == "" || endtime == "") {
                        toastr.error("Please fill In all fields");

                        return;
                    }

                    if (type == 1) {

                        window.location = '{{url('attendance/timesearch')}}?startdate=' + startdate + '&enddate=' + enddate + '&starttime=' + starttime + '&enddtime=' + endtime + '&type=1' + addtionalsearch;

                        return;
                    }
                    window.location = '{{url('attendance/timesearch')}}?startdate=' + startdate + '&enddate=' + enddate + '&starttime=' + starttime + '&enddtime=' + endtime + '&type=0' + addtionalsearch;
                }

                function viewMore(attendance_id)
                {
                    // $.get('{{ url('/attendance/getdetails') }}/'+attendance_id,function(data){
                    $("#detailLoader").load('{{ url('/attendance/getdetails') }}/'+attendance_id);
                    $('#attendanceDetailsModal').modal();
                    // });
                }

            </script>
            <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
            <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
            <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
            <script src="{{asset('global/vendor/jt-timepicker/jquery.timepicker.min.js')}}"></script>
            <script src="{{asset('global/vendor/datepair/datepair.min.js')}}"></script>
            <script src="{{asset('global/vendor/datepair/jquery.datepair.min.js')}}"></script>
            <script type="text/javascript"
                    src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
            <script type="text/javascript">
                $('#atttable').DataTable();
            </script>
@endsection