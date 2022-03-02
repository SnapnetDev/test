@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Performance Graph Report')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Performance Graph Report')}}</li>
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


                <!-- End First Row -->
                {{-- second row --}}
                <div class="col-ms-6 col-xs-6 col-md-6">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Graph Report for Balance Score Card</h3>
                            <div class="panel-actions">

                                <a class="btn btn-info "
                                   href="{{ url('bsc/excel_report?mp_id='.$mp->id.'&type=score') }}">Excel Report</a>

                            </div>
                        </div>
                        <div class="panel-body">

                            <canvas id="canvas_bsc" height="280" width="600"></canvas>

                        </div>
                    </div>

                </div>

                <div class="col-ms-6 col-xs-6 col-md-6">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Graph Report for Behavioral Assessment</h3>
                            <div class="panel-actions">

                                <a class="btn btn-info"
                                   href="{{ url('bsc/excel_report?mp_id='.$mp->id.'&type=behavioral_score') }}">Excel
                                    Report</a>

                            </div>
                        </div>
                        <div class="panel-body">

                            <canvas id="canvas_ba" height="280" width="600"></canvas>

                        </div>
                    </div>

                </div>

                <div class="col-ms-6 col-xs-6 col-md-6">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Graph Report for Performance Average</h3>
                            <div class="panel-actions">

                                <a class="btn btn-info"
                                   href="{{ url('bsc/excel_report?mp_id='.$mp->id.'&type=average') }}">Excel Report</a>

                            </div>
                        </div>
                        <div class="panel-body">

                            <canvas id="canvas_avg" height="280" width="600"></canvas>

                        </div>
                    </div>

                </div>

                <div class="col-ms-6 col-xs-6 col-md-6">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Graph Report for Departmental Performance</h3>
                            <div class="panel-actions">

                                <button class="btn btn-info" data-toggle="modal" data-target="#deptReportModal">View
                                    Bigger Report
                                </button>

                            </div>
                        </div>
                        <div class="panel-body">
                            <div>
                                <canvas id="canvas_dept" height="280" width="600"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- End Page -->
    @include('leave.modals.approve_request')
    {{-- Leave Request Details Modal --}}
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="deptReportModal" aria-hidden="true"
         aria-labelledby="deptReportModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Departmental Average Performance</h4>
                </div>
                <div class="modal-body">
                    <canvas id="dept_big_report"></canvas>
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
    <script src="{{ asset('global/vendor/chart-js/Chart.js')}}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js" charset="utf-8"></script> --}}
    <script type="text/javascript">
        let performanceMap = {};
        //getRandomColor()
        performanceMap['Poor Performance'] = '#f44336';
        performanceMap['Below Expectation'] = '#ffeb3b';
        performanceMap['Meets Expectation'] = '#8bc34a';
        performanceMap['Exceeds Expectation'] = '#076d0c';

        let performanceMapBorder = {};
        //getRandomColor()
        performanceMap['Poor Performance'] = '#cb1d1d';
        performanceMap['Below Expectation'] = '#ffc107';
        performanceMap['Meets Expectation'] = '#5c9a15';
        performanceMap['Exceeds Expectation'] = '#064109';


        $(document).ready(function () {
            $('.input-daterange').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

        });

        function approve(leave_approval_id) {
            $(document).ready(function () {
                $('#approval_id').val(leave_approval_id);
                $('#approveLeaveRequestModal').modal();
            });

        }

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

    </script>
    <script>

        function drawchart(url, canvas, title) {

            $.get(url, function (response) {
                let rcolor = getRandomColor();

                var options = {
                    type: 'line',
                    data: {
                        labels: response.labels,
                        datasets: [
                            {
                                label: title,
                                data: response.data,
                                backgroundColor: rcolor,
                                borderColor: rcolor,
                                hoverBackgroundColor: rcolor,
                                borderWidth: 2,
                            }
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    reverse: false
                                }
                            }]
                        }
                    }
                }
                var myLineChart = new Chart(document.getElementById(canvas).getContext("2d"), options);
            });

        }

        $(document).ready(function () {
            drawchart("{{url('bsc/bsc_mp_report?mp_id='.$mp->id)}}", "canvas_bsc", "Bsc Assessment");
            drawchart("{{url('bsc/ba_mp_report?mp_id='.$mp->id)}}", "canvas_ba", "Behavioral Assessment");
            drawchart("{{url('bsc/avg_report?mp_id='.$mp->id)}}", "canvas_avg", "Average Assessment");

        });
    </script>
    <script>


        $(document).ready(function () {
            $.get("{{url('bsc/dept_report?mp_id='.$mp->id)}}", function (response) {
                console.log(response.datasets);
                let dt = [];

                $.each(response.datasets, function (k, v) {

                    dt.push({
                        label: v.label,
                        data: v.data,
                        backgroundColor:  performanceMap[v.label],
                        borderColor:  performanceMapBorder[v.label],
                        hoverBackgroundColor: performanceMapBorder[v.label],
                        borderWidth: 2,
                    });

                });

                // var barChartData = response;
                var abarChartData = {
                    labels: response.labels,
                    datasets: dt
                };

                var ctx = document.getElementById('canvas_dept');

                var myChart = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: abarChartData,
                    options: {
                        scales: {
                            xAxes: [{stacked: true}],
                            yAxes: [{stacked: true}]
                        }
                    }
                });
            });


        });
    </script>
    <script>


        $(document).ready(function () {
            $.get("{{url('bsc/dept_report?mp_id='.$mp->id)}}", function (response) {
                console.log(response.datasets);
                let dt = [];



                $.each(response.datasets, function (k, v) {


                    dt.push({
                        label: v.label,
                        data: v.data,
                        backgroundColor: performanceMap[v.label],
                        borderColor: performanceMapBorder[v.label],
                        hoverBackgroundColor: performanceMapBorder[v.label],
                        borderWidth: 2,
                    });

                });

                // var barChartData = response;
                var abarChartData = {
                    labels: response.labels,
                    datasets: dt
                };

                var ctx = document.getElementById('dept_big_report');

                var myChart = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: abarChartData,
                    options: {
                        scales: {
                            xAxes: [{stacked: true}],
                            yAxes: [{stacked: true}]
                        }
                    }
                });
            });


        });
    </script>
@endsection
