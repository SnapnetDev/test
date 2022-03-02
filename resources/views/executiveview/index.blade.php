@extends('layouts.master')
@section('stylesheets')

@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('People Analytics')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('People Analytics')}}</li>
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
            {{-- <iframe width="100%" height="800" src="https://app.powerbi.com/view?r=eyJrIjoiODQxNTM1ZjAtZWY3My00MDMyLTljNTktM2YzMGM5ZDZlNTIyIiwidCI6ImJhMTMwZWNhLTMwMzAtNDhlMS05MDg5LWM5NzkyOTNhZWI3MCIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe> --}}
            <button class="btn btn-primary" id="printData">Print Current View</button>
            <div id="reportContainer" style="height: 900px;"></div>

        </div>

    </div>
    <!-- End Page -->
@endsection
@section('scripts')
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset('global/vendor/powerbi/dist/powerbi.js')}}"></script>
    <script type="text/javascript">
        function loadUrl(url) {
            window.location = url;
        }

        $(function () {
            // Get models. models contains enums that can be used.
            var models = window['powerbi-client'].models;

                    @if(isset($_GET['page']) && $_GET['page']=='quickinsight')
            var config =
                    {
                        type: 'qna',
                        tokenType: models.TokenType.Embed,
                        accessToken: '{{$accessToken}}',
                        embedUrl: 'https://app.powerbi.com/qnaEmbed?groupId={{$groupId}}',
                        datasetIds: ['{{$dataSetId}}'],
                        viewMode: models.QnaMode['Interactive']
                    };

            // Get a reference to the embedded QNA HTML element
            var qnaContainer = $('#reportContainer')[0];
            // Embed the QNA and display it within the div container.
            var qna = powerbi.embed(qnaContainer, config);
            // qna.off removes a given event handler if it exists.
            qna.off("loaded");
                    @else

            var embedConfiguration =
                    {
                        type: 'report',

                        id: '{{$reportId}}',
                        embedUrl: 'https://app.powerbi.com/reportEmbed?reportId={{$reportId}}&groupId={{$groupId}}',
                        tokenType: models.TokenType.Aad,
                        accessToken: '{{$accessToken}}',
                        settings:
                            {
                                filterPaneEnabled: false,
                                navContentPaneEnabled: false
                            }
                    };

            var $reportContainer = $('#reportContainer');
            var report = powerbi.embed($reportContainer.get(0), embedConfiguration);
            demographics_report = report.page('ReportSection');

            var comps = "@json($filtered)";
            console.log(comps);
            var customFilter = filterVar(comps);
            // auto filter report to one company
            report.on('loaded', function (event) {
                demographics_report.setFilters([customFilter])
                    .catch(errors => {
                        console.log(errors);
                    });
            });
            @endif



            $('#printData').click(function () {
                var report = powerbi.get($reportContainer.get(0));
                report.print()
            });

            function filterVar(companyValue) {
                var filterCompany = {
                    $schema: "http://powerbi.com/product/schema#basic",
                    target: {
                        table: "Companies",
                        column: "id"
                    },

                    operator: "In",
                    values: companyValue,
                    filterType: 1, // pbi.models.FilterType.BasicFilter,
                    displaySettings: {
                        isHiddenInViewMode: false
                    }
                };

                return filterCompany;

            }

        });


    </script>

@endsection
