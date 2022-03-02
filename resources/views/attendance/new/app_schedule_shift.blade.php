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
            <h1 class="page-title">{{__('Schedule Shift')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Schedule Shift')}}</li>
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
                <div class="col-lg-5"></div>
                <div class="col-lg-7">
                    <form method="GET" action="{{route("app.schedule.shift")}}" style="text-align: right">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon fa fa-calendar"
                                                                   aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="startdate"
                                       data-plugin="datepicker" name="date" autocomplete="off"
                                       placeholder="{{ $date->format('d M, Y') }}" value="{{ $date->format('m/d/Y') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="days" required>
                                    <option value="">Select Days</option>
                                    @for($i=5; $i<=14;$i++)
                                        <option value="{{$i}}" {{request()->get('days')==$i ? 'selected' : ''}}>{{$i}} Days</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" name="department" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}" {{request()->get('department')==$department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <br>
            <div class="col-md-12 col-xs-12 col-md-12">
                <form id="submitShiftScheduleForm">
                    @csrf
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Shift Schedule</h3>
                            <div class="panel-actions">
                                <button class="btn btn-info">
                                    <a style="text-decoration: none; color: white"
                                       href='{{ route('app.schedule.shift',['date'=>$date->format('m/d/Y'),'days'=>request()->get('days'),'department'=>request()->get('department'),'type'=>'excel']) }}'>
                                        Download Report</a>
                                </button>
                                <button class="btn btn-success" type="submit">
                                    Submit
                                </button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                    <thead>
                                    <tr>
                                        <th>Staff Name</th>
                                        @foreach($dates as $date)
                                            <th>{{ $date }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td><a style="text-decoration: none;" href="{{ route('attendance.staff',$user->id) }}">{{$user->name}}</a></td>
                                            @foreach($dates as $date)
                                                @php($user_shift=$users_shifts->where('user_id',$user->id)->where('sdate',$date)->first())
                                                <td>
                                                    @php($lr=$leave_requests->where('user_id',$user->id)->where('start_date','<=',$date)->where('end_date','>=',$date)->first())
                                                    @if($lr)
                                                        {{ isset($lr->leave->name) ? '' : 'A-Leave' }}
                                                        @if($lr->leave->name=='Sick Leave')
                                                            S-Leave
                                                        @elseif($lr->leave->name=='Casual Leave')
                                                            C-Leave
                                                        @endif
                                                        <span style="cursor: pointer; color: green" onclick="activateShift('{{$user->id}}{{$date}}')">Assign Shift</span>
                                                    @endif
                                                    <select style="{{ ($lr) ? "display: none": '' }}" class="form-control" name="shift[{{$user->id}}][{{$date}}]" id="{{$user->id}}{{$date}}">
                                                        <option value="">Select Shift</option>
                                                        @foreach($shifts as $shift)
                                                            <option value="{{$shift->id}}"
                                                                    {{ ($user_shift && $user_shift->shift->id==$shift->id) ? 'selected' : ''}}
                                                            >{{$shift->type}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        function activateShift(id){
            $("#"+id).show()
            $(this).hide()
        }
    </script>
    <script>
        $('#submitShiftScheduleForm').on('submit', function (event) {
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            console.log(formdata);
            $.ajax({
                url: '{{route('app.schedule.shift.submit')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    toastr.success("Shift Scheduled successfully", 'Success');
                    //location.reload();

                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr.error(valchild[0]);
                        });
                    });
                }
            });
            return event.preventDefault();
        });
    </script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('global/vendor/jt-timepicker/jquery.timepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/datepair.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/jquery.datepair.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
@endsection