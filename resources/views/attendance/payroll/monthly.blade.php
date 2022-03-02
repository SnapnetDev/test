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
            <h1 class="page-title">{{__('Monthly Financial Report for ')}}  {{ $date->format('M, Y') }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Monthly Financial Report')}}</li>
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
            <div class="col-md-12 col-xs-12 col-md-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Financial Report for {{ $date->format('M Y') }} from {{ $payroll->start }} to {{ $payroll->end }}</h3>
                        <div class="panel-actions">
                            <button class="btn btn-info">
                                <a  style="text-decoration: none; color: white" href="{{ route('monthly.attendance.payroll',$payroll->id) }}?type=excel">Download Report</a>
                            </button>
                            <button class="btn btn-info">
                                <a  style="text-decoration: none; color: white" href=''> Send Payslips</a>
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                            <thead>
                            <tr>
                                <th>EMPID</th>
                                <th>{{__('NAME')}}</th>
                                <th>{{__('ROLE')}}</th>
                                <th>{{__('DAYS EARLY')}}</th>
                                <th>{{__('DAYS LATE')}}</th>
                                <th>{{__('DAYS ABSENT')}}</th>
                                <th>{{__('DAYS OFF')}}</th>
                                <th>{{__('Max Expected')}}</th>
                                <th>{{__('Amount Paid')}}</th>
                                <th>{{__('ACTION')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($users_payrolls as $pay)
                                <tr>
                                    <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$pay->user->id) }}?from={{ $payroll->start }}&to={{ $payroll->end }}">{{$pay->user->emp_num}}</a></td>
                                    <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$pay->user->id) }}?from={{ $payroll->start }}&to={{ $payroll->end }}">{{$pay->user->name}}</a></td>
                                    <td>{{$pay->role->name}}</td>
                                    <td>{{$pay->early}}</td>
                                    <td>{{$pay->late}}</td>
                                    <td>{{$pay->absent}}</td>
                                    <td>{{$pay->off}}</td>
                                    <td>{{number_format($pay->amount_expected,2)}}</td>
                                    <td>{{number_format($pay->amount_received,2)}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                                <a style="cursor:pointer;" class="dropdown-item"  href="{{ route('recalculate.attendance.payroll',$pay->id) }}"> &nbsp;Recalculate</a>
                                                <a style="cursor:pointer;" class="dropdown-item"  href=""> &nbsp;Send Payslip</a>
                                                <a style="cursor:pointer;" class="dropdown-item"  href="{{ route('attendance.payroll.download.payslip',$pay->id) }}"> &nbsp;Download Payslip</a>

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
    <input type="hidden" value="{{ $date->format('Y-m-d') }}" id="getdate">



 {{--   <div class="modal fade in modal-3d-flip-horizontal modal-info" id="downloadreportModal" aria-hidden="true" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Download Report per Role</h4>
                </div>
                <div class="modal-body">
                    <form class="form-inline" action="{{ route('monthly.financial')}}" method="GET">
                        <input type="hidden" name="date" value="{{$date->format('m-Y')}}">
                        <input type="hidden" name="type" value="excel">
                        <div class="row">

                        </div>
                        @foreach(\App\Role::all() as $role)
                            <div class="col-md-4">
                                <input type="checkbox" id="role{{$role->id}}" name="roles[]" value="{{ $role->id }}">
                                <label for="role{{$role->id}}">{{ $role->name }}</label>
                            </div>
                        @endforeach
                        <br>
                        <button class="btn btn-info">Download Report</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
--}}
@endsection
@section('scripts')
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('global/vendor/jt-timepicker/jquery.timepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/datepair.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/jquery.datepair.min.js')}}"></script>
    <script>
        $("#date2").datepicker({
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months",
            orientation: "bottom"
        });
    </script>

    <script type="text/javascript">

    </script>
@endsection