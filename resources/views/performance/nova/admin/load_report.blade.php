<div class="col-lg-12">
    <div class="panel panel-info panel-line" id="messgedd">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $measurement_period->name }} Report </h3>
        </div>
        <div class="panel-body">

            <div class="row" data-plugin="matchHeight" data-by-row="true">
                <div class="col-ms-12 col-xs-12 col-md-12">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">General Graph Report</h3>
                            <div class="panel-actions">
                                <a class="btn btn-info "
                                   href="{{ route('measurement.period.export.report') }}?measurement_period={{$measurement_period->id}}">Excel Report</a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <canvas id="canvas_general" height="280" width="600"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-ms-12 col-xs-12 col-md-12">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Departmental Graph Report</h3>
                            <div class="panel-actions">
                                <a class="btn btn-info"
                                   href="{{ route('measurement.period.export.report') }}?measurement_period={{$measurement_period->id}}">Excel Report</a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <canvas id="canvas_departmental" height="280" width="600"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="{{ asset('global/vendor/chart-js/Chart.js')}}"></script>
<script>
    let performanceMap = {};
    //getRandomColor()
    performanceMap['Poor'] = '#f44336';
    performanceMap['Below Average'] = '#ffc226';
    performanceMap['Average'] = '#ffeb3b';
    performanceMap['Above Average'] = '#07e814';
    performanceMap['Outstanding'] = '#076d0c';

    let performanceMapBorder = {};
    //getRandomColor()
    performanceMap['Poor'] = '#f44336';
    performanceMap['Below Average'] = '#ffc226';
    performanceMap['Average'] = '#ffeb3b';
    performanceMap['Above Average'] = '#07e814';
    performanceMap['Outstanding'] = '#076d0c';

    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function drawchart(type, canvas, title) {
        url='{{url('load/kpi-report-data?measurement_period='.$measurement_period->id)}}&type='+type;
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
        drawchart("general", "canvas_general", "General");
    });

    $(document).ready(function () {
        url='{{url('load/kpi-report-data?measurement_period='.$measurement_period->id)}}&type=departmental';
        $.get(url, function (response) {
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

            var ctx = document.getElementById('canvas_departmental');

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
