@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/jt-timepicker/jquery-timepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
    <style>
        td.details-control {
            background: url('http://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('http://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
        }
    </style>
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{ $user->name }} KPIs</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{ $user->name }} KPIs</li>
            </ol>
            <div class="page-header-actions">
                <div class="row no-space w-350 hidden-sm-down"></div>
            </div>
        </div>

        <div class="page-content container-fluid">
            <div class="row">
                <div class="col-lg-3 masonry-item">
                    <div class="panel" id="messge">
                        <div class="panel-heading">
                            <h3 class="panel-title">Measurement Periods</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group list-group-full h-500" data-plugin="scrollable">
                                <div data-role="container">
                                    <div data-role="content" id="table1">
                                        <input type="text" id="search1" name="example-input3-group2" class="form-control" placeholder="Search">
                                        <br>
                                        @foreach($kpi_periods as $period)
                                            <a href="javascript:void(0)" onclick="loadMeasurementPeriodKPI({{$period->id}})" class="list-group-item" style="padding-bottom: 0px; margin-bottom: -10px;">
                                                <h5 class="list-group-item-heading mt-0 mb-0">
                                                    {{ $period->name }}
                                                </h5>
                                                <dl class="dl-horizontal row">
                                                    <dt class="col-sm-3">From:</dt>
                                                    <dd class="col-sm-9">{{ $period->from }}</dd>
                                                    <dt class="col-sm-3">To:</dt>
                                                    <dd class="col-sm-9">{{ $period->to }}</dd>
                                                    <dt class="col-sm-3">Status:</dt>
                                                    <dd class="col-sm-9">{{ $period->status }}</dd>
                                                </dl>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 col-xs-12 col-md-9" id="measurement_period_kpis"></div>
            </div>

            <br>


        </div>
    </div>
    
@endsection
@section('scripts')
    <script>
        function loadMeasurementPeriodKPI(period) {
            url = '{{url('user/kpi/measurement_period')}}?measurement_period='+period+'&user='+'{{ $user->id }}',
                $("#measurement_period_kpis").load(url, function() {
                    console.log('done');
                });
        }
    </script>
    <script>
        $(document).ready(function(){
            $("#search1").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#table1 a").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    <script src="{{asset('global/js/Plugin/table.min.js')}}"></script>
@endsection