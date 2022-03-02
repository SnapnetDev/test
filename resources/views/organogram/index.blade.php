@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Gochi+Hand" rel="stylesheet">

    <style media="screen">


        #tree {
            width: 100%;
            height: 100%;
        }
    </style>

@endsection
@section('content')
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">Organogram</h1>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content container-fluid">
            <div class="row">

                <div class="col-md-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                            {{ session('success') }}
                        </div>
                    @elseif (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="panel panel-info ">
                        <div class="panel-heading main-color-bg">
                            <h3 class="panel-title">Team Lead</h3>
                        </div>

                        <div class="panel-body">

                            <div id="tree"></div>
                        </div>
                        <div class="panel-footer">


                        </div>

                    </div>


                </div>
            </div>

        </div>


    </div>
@endsection
@section('scripts')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/raphael/raphael.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/balkan/orgchart.js')}}"></script>
    <script type="text/javascript">

        window.onload = function () {
            var chart = new OrgChart(document.getElementById("tree"), {
                mouseScrool: OrgChart.action.none,
                template: "mila",
                enableDragDrop: false,

                menu: {
                    pdf: { text: "Export PDF" },
                    png: { text: "Export PNG" },
                    svg: { text: "Export SVG" },
                    csv: { text: "Export CSV" }
                },
                nodeBinding: {
                    field_0: "name",
                    field_1: "title",
                    field_2: "phone",
                },
                slinks: [
                    @foreach($positions as $position)
                        @if($position->sp_id>0)
                    {from:"{{$position->id}}", to: "{{$position->sp_id}}",template: 'yellow', label: ''},
                    @endif
                    @endforeach
                ],
                nodes: [
                    @foreach($positions as $position)
                    { id: "{{$position->id}}",pid: "{{$position->p_id}}", name: "{{$position->user?$position->user->name:''}}", title: "{{$position->name}}",phone:"{{$position->level->name}}" },
                    @endforeach
                    // { id: 1, name: "Billy Moore", title: "CEO", img: "https://cdn.balkan.app/shared/2.jpg" },
                    // { id: 2, pid: 1, name: "Billie Rose", title: "Dev Team Lead", img: "https://cdn.balkan.app/shared/5.jpg" },
                    // { id: 3, pid: 1, name: "Glenn Bell", title: "HR", img: "https://cdn.balkan.app/shared/10.jpg" },
                    // { id: 4, pid: 1, name: "Blair Francis", title: "HR", img: "https://cdn.balkan.app/shared/11.jpg" },
                    //
                    // { id: 5, pid: 3, name: "Skye Terrell", title: "Manager", img: "https://cdn.balkan.app/shared/12.jpg" },
                    // { id: 6, pid: 3, name: "Jordan Harris", title: "JS Developer", img: "https://cdn.balkan.app/shared/6.jpg" },
                    // { id: 7, pid: 3, name: "Will Woods", title: "JS Developer", img: "https://cdn.balkan.app/shared/7.jpg" }
                ]
            });
        };


    </script>
@endsection
