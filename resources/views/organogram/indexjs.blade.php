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
    @include('organogram.modals.addorganogramposition')
    {{-- edit IP modal --}}
    @include('organogram.modals.editorganogramposition')
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
        var editForm = function () {
            this.nodeId = null;
        };

        editForm.prototype.init = function (obj) {
            var that = this;
            // var node = chart.get(that.nodeId);


        };

        editForm.prototype.show = function (node_id) {

            $.get('{{ url('/organograms/get_organogram_position') }}',{company_organogram_position_id:node_id},function(data){
                // console.log(data);
                $('#editopid').val(data.id);
                $('#editopname').val(data.name);
                $('#editopuser_id').val(data.user_id);

                $('#editopp_id').val(data.p_id);
                $('#editopsp_id').val(data.sp_id);
                $('#editopcompany_organogram_level_id').val(data.company_organogram_level_id);

            });
            $('#editOrganogramPositionModal').modal();
        };

        editForm.prototype.hide = function (showldUpdateTheNode) {

        };

        var chart = new OrgChart(document.getElementById("tree"), {
            editUI: new editForm(),
            template: "mila",
            layout: OrgChart.tree,
            enableDragDrop: true,
            @if(Auth::user()->role->permissions->contains('constant', 'manage_organogram'))
            nodeMenu: {
                edit: { text: "Edit" },
                add: { text: "Add" },
                remove: { text: "Remove" }
            },
            @endif
            menu: {
                pdf: { text: "Export PDF" },
                png: { text: "Export PNG" },
                svg: { text: "Export SVG" },
                csv: { text: "Export CSV" }
            },
            nodeBinding: {
                field_0: "name",
                field_1: "position",
                field_3: "level",
            },
            slinks: [
                @foreach($organogram_positions as $position)
                @if($position->sp_id>0)
            {from:"{{$position->id}}", to: "{{$position->sp_id}}",template: 'yellow', label: ''},
            @endif
            @endforeach
        ],
            nodes: [
                @foreach($organogram_positions as $position)
            { id: "{{$position->id}}",pid: "{{$position->p_id}}", name: "{{$position->user?$position->user->name:''}}", position: "{{$position->name}}",level:"{{$position->level?$position->level->name:''}}" },
            @endforeach
        ]
        });




        chart.on('add', function (sender, node) {


            $('#parent_node_id').val(parseInt(node.pid));
            $('#addOrganogramPositionModal').modal();

            return false;
        });

        chart.on('remove', function (sender, nodeId) {
            $.get('{{ url('organograms/delete_organogram_position') }}',{company_organogram_position_id:nodeId})
        .done(function () {
                sender.removeNode(nodeId);
            })
            return true;
        });

        {{--$.get('{{ url('organograms/get_organogram_positions') }}',{organogram_id:{{$organogram->id}} }).done(function (response) {--}}
        {{--    chart.load(response.nodes);--}}
        {{--});--}}

        $(function() {

            $('#addOrganogramPositionForm').submit(function(e){
                e.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url         : '{{url('organograms')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){

                        toastr["success"]("Changes saved successfully",'Success');
                        $('#addOrganogramPositionModal').modal('toggle');
                        location.reload();
                    },
                    error:function(data, textStatus, jqXHR){
                        jQuery.each( data['responseJSON'], function( i, val ) {
                            jQuery.each( val, function( i, valchild ) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });

            $('#editOrganogramPositionForm').submit(function(e){
                e.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url         : '{{url('organograms')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){

                        toastr["success"]("Changes saved successfully",'Success');
                        $('#editOrganogramPositionModal').modal('toggle');
                        location.reload();
                    },
                    error:function(data, textStatus, jqXHR){
                        jQuery.each( data['responseJSON'], function( i, val ) {
                            jQuery.each( val, function( i, valchild ) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });






        });

    </script>
@endsection
