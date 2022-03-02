@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/jt-timepicker/jquery-timepicker.css') }}">
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Manual Attendance')}}  {{ $date->format('d M, Y') }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Manual Attendance')}}</li>
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
                    <form method="GET" action="{{route("manual.attendance")}}">
                        <div class="col-md-7" style="margin-left:-40px">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon fa fa-calendar"
                                                                   aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="startdate"
                                       data-plugin="datepicker" name="date" autocomplete="off"
                                       placeholder="{{ $date->format('d M, Y') }}" value="{{ $date->format('m/d/Y') }}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <br>

            <div class="col-md-12 col-xs-12 col-md-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Manual Attendance Report for {{ $date->format('d M, Y') }}</h3>
                        <div class="panel-actions">
                            <button class="btn btn-info">
                                <a style="text-decoration: none; color: white"  href='{{ route('manual.attendance',['date'=>$date->format('m/d/Y'),'type'=>'excel']) }}'>
                                    Download Report</a>
                            </button>
                          <button class="btn btn-info" onclick="addNew()">
                                Add New
                          </button>
                          <button class="btn btn-info" onclick="addBulk()">
                                Add Bulk
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
                                    <th>{{__('TIME IN')}}</th>
                                    <th>{{__('TIME OUT')}}</th>
                                    <th>{{__('REASON')}}</th>
                                    <th>{{__('STATUS')}}</th>
                                    <th>{{__('ACTION')}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(count($manuals)>0)
                                    @foreach($manuals as $manual)
                                        <tr>
                                            <td><a style="text-decoration: none;" href="{{ route('attendance.staff',$manual->user->id) }}">{{$manual->user->emp_num}}</a></td>
                                            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$manual->user->id) }}">{{$manual->user->name}}</a></td>
                                            <td>
                                                <span class="text text-success"><b>{{$manual->time_in}}</b></span>
                                            </td><td>
                                                <span class="text text-success"><b>{{$manual->time_out}}</b></span>
                                            </td>
                                            <td>{{$manual->reason}}</td>
                                            <td>
                                                <span style="cursor: pointer"
                                                        data-content="@foreach($manual->workflow_details as $detail) Stage {{ $detail['position'] }}: {{ $detail['status'] }}@endforeach"
                                                        data-toggle="popover" data-original-title="Approval Details"
                                                        tabindex="0" title="">{{$manual->status}}</span>

                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                                        @if($manual->status=='pending' && in_array(Auth::id(),collect($manual->workflow_details)->where('status','pending')->first()['users'] ))
                                                            <a style="cursor:pointer; text-decoration: none;" class="dropdown-item" href="#">
                                                           {{-- <i class="fa fa-eye" aria-hidden="true"></i>--}}&nbsp;Edit</a>
                                                        {{--@if(Auth::id()==$manual->ssm_id)--}}
                                                            <a onclick="approve({{$manual->id}})" style="cursor:pointer; text-decoration: none;" class="dropdown-item" href="#">&nbsp;Approve</a>
                                                            <a onclick="decline({{$manual->id}})" style="cursor:pointer; text-decoration: none;" class="dropdown-item" href="#">&nbsp;Decline</a>
                                                        {{--@endif--}}
                                                        @endif
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" style="text-align: center">
                                            <b style="font-size:20px;"
                                               class="text-success"> {{__('No Manual Attendance For Today Yet')}}</b>
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
                    <h4 class="modal-title" id="training_title">Add Manual Attendance</h4>
                </div>
                <form id="addnewForm" method="Post">
                    @csrf
                    <input type="hidden" value="{{$date->format('Y-m-d')}}" name="date">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <h4 class="example-title">User</h4>
                                    <select class="form-control" name="user_id" required>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <h4 class="example-title">Time In</h4>
                                    <div class="input-group clockpicker-wrap" data-plugin="clockpicker">
                                        <input type="time" data-plugin="clockpicker" class="form-control" name="time_in" autocomplete="off">
                                        <span class="input-group-addon">
                                            <span class="md-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <h4 class="example-title">Time Out</h4>
                                    <div class="input-group clockpicker-wrap" >
                                        <input type="time" class=" clockpicker form-control" name="time_out" autocomplete="off" required>
                                        <span class="input-group-addon">
                                          <span class="md-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <h4 class="example-title">Reason</h4>
                                    <select class="form-control" name="reason" required>
                                        <option value="faulty">Faulty Device</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ csrf_field() }}
                                <button id="submit_button" type="submit" class="btn btn-info pull-left">Submit</button>
                                <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                            </div>
                            <!-- End Example Textarea -->
                        </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- End Page -->
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="addBulkModal" aria-hidden="true"
         aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Add Bulk Manual Attendance</h4>
                </div>
                <form id="addnewBulkForm" method="Post">
                    @csrf
                    <input type="hidden" value="{{$date->format('Y-m-d')}}" name="date">
                    <div class="modal-body">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <a href="{{ route('manual.attendance.excel.template') }}?date={{$date->format('Y-m-d')}}">Download Template</a>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="inputBasicFirstName">Upload Manual Attendance</label>
                                <input type="file" name="template" class="form-control" required>
                            </div>
                                <br>
                                <div class="form-row">
                                    <div id="loader" style="display: none;" class="loader vertical-align-middle loader-ellipsis"></div>
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <button id="submit_button2" type="submit" class="btn btn-info pull-left">Submit</button>
                                <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                            </div>
                            <!-- End Example Textarea -->
                        </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
    <script type="text/javascript">
        function viewMore(attendance_id) {
            // $.get('{{ url('/attendance/getdetails') }}/'+attendance_id,function(data){
            $("#detailLoader").load('{{ url('/attendance/getdetails') }}/' + attendance_id);
            $('#attendanceDetailsModal').modal();
            // });
        }

        function addNew() {
            $('#attendanceDetailsModal').modal();
        }

        function addBulk() {
            $('#addBulkModal').modal();
        }

    </script>
    <script>
        function approve(id){
            alertify.confirm('Are you sure you want to Approve this Manual Attendance', function () {
                alertify.success('Processing this request. Please wait...');
                sendAjax(id,1)
            }, function () {
                alertify.error('Cancelled')
            })
        }
        function decline(id){
            alertify.confirm('Are you sure you want to Decline this Manual Attendance', function () {
                alertify.success('Processing this request. Please wait...');
                sendAjax(id,2)
            }, function () {
                alertify.error('Cancelled')
            })
        }

        function sendAjax(id,stat){
            $.ajax({
                url: '{{url('attendance/manual/approval')}}/'+id+'?status='+stat,
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
        $(document).on('submit', '#addnewForm', function (event) {
            $("#submit_button").hide();
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{route('manual.attendance.store')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    console.log(data)
                    toastr.success("Changes saved successfully", 'Success');
                    $("#detailsText").html(data.details)
                    $("#submit_button").show();
                    $('#attendanceDetailsModal').modal('toggle');
                    setTimeout(function(){
                        window.location.reload();
                    },2000);

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

        $(document).on('submit', '#addnewBulkForm', function (event) {
            $("#submit_button2").hide();
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{route('manual.attendance.excel')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    console.log(data)
                    toastr.success("Manual Attendance Uploaded successfully", 'Success');
                    $("#submit_button2").show();
                    $('#addBulkModal').modal('toggle');
                    setTimeout(function(){
                        window.location.reload();
                    },2000);

                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr.error(valchild[0]);
                        });
                    });
                    $("#submit_button2").show();
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
@endsection

@section('stylesheets')
    <style>
        /* width */ ::-webkit-scrollbar { width: 10px;  height: 4px;  border-radius: 5px; } /* Track */
        ::-webkit-scrollbar-track { background: #f1f1f1; } /* Handle */ ::-webkit-scrollbar-thumb { background: #888; }
        /* Handle on hover */ ::-webkit-scrollbar-thumb:hover { background: #555; }


    </style>
@endsection