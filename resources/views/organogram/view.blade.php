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

        var chart = new OrgChart(document.getElementById("tree"), {
            enableDragDrop: true,
            nodeMenu: {
                edit: { text: "Edit" },
                add: { text: "Add" },
                remove: { text: "Remove" }
            },
            nodeBinding: {
                field_0: "fullName"
            }
        });

        chart.on('update', function (sender, oldNode, newNode) {
            $.post("{{url('organograms')}}", newNode)
        .done(function () {
                sender.updateNode(newNode);
            });
            return false;
        });

        chart.on('add', function (sender, node) {
            node.id = 0;
            node.pid = parseInt(node.pid);
            node.fullName = "John Smith";

            $.post("@Url.Action("AddNode")", node)
        .done(function (response) {
                debugger;
                node.id = response.id;
                sender.addNode(node);
            })

            return false;
        });

        chart.on('remove', function (sender, nodeId) {
            $.post("@Url.Action("RemoveNode")", { id: nodeId })
        .done(function () {
                sender.removeNode(nodeId);
            })
            return true;
        });

        $.get("@Url.Action("Read")").done(function (response) {
            chart.load(response.nodes);
        });
    </script>
@endsection
