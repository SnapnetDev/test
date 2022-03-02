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
            <h1 class="page-title">{{__('Staff Lateness Report')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Staff Lateness Report')}}</li>
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
                    <form method="GET" action="{{route('lateness.report')}}" style="text-align: right">
                        <div class="col-lg-5">
                            <div class="input-group">
                                <span class="input-group-addon">From <i class="icon fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="startdate" data-plugin="datepicker" name="from" autocomplete="off" placeholder="{{$start}}" required>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group"><span class="input-group-addon">To <i class="icon fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="enddate" data-plugin="datepicker" name="to"  autocomplete="off" placeholder="{{$end}}" required>
                            </div>
                        </div>
                        <div class="col-lg-2" >
                            <button title="Export to Excel" class="btn btn-success btn-sm" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <br>

            <div class="col-md-6 col-xs-6 col-md-6">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Staff Early Report from {{ $start }} to {{$end}}</h3>
                        <div class="panel-actions">

                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- Example Tabs Solid Left -->
                        <div class="example-wrap">
                            <div class="nav-tabs-vertical d-flex" data-plugin="tabs">
                                <ul class="nav nav-tabs nav-tabs-solid" role="tablist">

                                    @for($a=0; $a<count($dates); $a++)
                                        <li class="nav-item" role="presentation"><a class="nav-link {{ $a==0 ? 'active': '' }}" data-toggle="tab" href="#early{{$a}}" aria-controls="early{{ $a }}" role="tab" aria-selected="true">{{$dates[$a]}}</a></li>
                                    @endfor
                                </ul>
                                <div class="tab-content">
                                    @for($a=0; $a<count($datas); $a++)
                                        <div class="tab-pane {{ $a==0 ? 'active': '' }}" id="early{{ $a }}" role="tabpanel">
                                            <table class="table table-hover dataTable  w-full" data-plugin="dataTable">
                                                <thead>
                                                <tr>
                                                    <th>Staff</th>
                                                    <th>Clock In</th>
                                                    <th>Shift Time</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($datas[$a] as $da)
                                                    <tr>
                                                        <td>{{ $da->user->name }}</td>
                                                        <td>{{ $da['first_clockin'] }}</td>
                                                        <td>{{ $da['shift_start'] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <!-- End Example Tabs Solid Left -->
                    </div>
                </div>
            </div>


            <div class="col-md-6 col-xs-6 col-md-6">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Staff Lateness Report from {{ $start }} to {{$end}}</h3>
                        <div class="panel-actions">

                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- Example Tabs Solid Left -->
                        <div class="example-wrap">
                            <div class="nav-tabs-vertical d-flex" data-plugin="tabs">
                                <ul class="nav nav-tabs nav-tabs-solid" role="tablist">

                                    @for($a=0; $a<count($dates); $a++)
                                        <li class="nav-item" role="presentation"><a class="nav-link {{ $a==0 ? 'active': '' }}" data-toggle="tab" href="#late{{$a}}" aria-controls="late{{ $a }}" role="tab" aria-selected="true">{{$dates[$a]}}</a></li>
                                    @endfor
                                </ul>
                                <div class="tab-content">
                                    @for($a=0; $a<count($datas2); $a++)
                                        <div class="tab-pane {{ $a==0 ? 'active': '' }}" id="late{{ $a }}" role="tabpanel">
                                            <table class="table table-hover dataTable w-full" data-plugin="dataTable">
                                                <thead>
                                                <tr>
                                                    <th>Staff</th>
                                                    <th>Clock In</th>
                                                    <th>Shift Time</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($datas2[$a] as $da)
                                                    <tr>
                                                        <td>{{ $da->user->name }}</td>
                                                        <td>{{ $da['first_clockin'] }}</td>
                                                        <td>{{ $da['shift_start'] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <!-- End Example Tabs Solid Left -->
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

                function viewMore(attendance_id) {
                    // $.get('{{ url('/attendance/getdetails') }}/'+attendance_id,function(data){
                    $("#detailLoader").load('{{ url('/attendance/getdetails') }}/' + attendance_id);
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