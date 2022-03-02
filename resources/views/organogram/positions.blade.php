@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
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
                            <h3 class="panel-title">Organogram Positions</h3>
                            <div class="panel-actions">
                                <button class="btn btn-info" data-toggle="modal" data-target="#addOrganogramPositionModal">Add Organogram Position</button>

                            </div>
                        </div>

                        <div class="panel-body">

                            <table class="table table-striped" >
                                <thead>
                                <tr>
                                    <th>Position Name</th>
                                    <th>Level</th>
                                    <th>Employee Name</th>
                                    <th>Parent Position Name</th>
                                    <th>Second Parent Position Name</th>
                                    <th>Updated By</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($organogram_positions as $position)
                                    <tr>
                                        <td>{{$position->name}}</td>
                                        <td>{{$position->level?$position->level->name:''}}</td>
                                        <td>{{$position->user?$position->user->name:''}}</td>
                                        <td>{{$position->parent?$position->parent->name:'Root'}}</td>
                                        <td>{{$position->second_parent?$position->second_parent->name:'None'}}</td>
                                        <td>{{$position->updater?$position->updater->name:''}}</td>
                                        <td><a class="" title="edit" class="btn btn-icon btn-info" id="{{$position->id}}" onclick="prepareOrganogramPositonEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$position->id}}" onclick="deleteOrganogramPosition(this.id);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
    <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
        <script>
        $(function() {
        $('.table').dataTable();
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
        function prepareOrganogramPositonEditData(company_organogram_position_id){
            $.get('{{ url('/organograms/get_organogram_position') }}',{company_organogram_position_id:company_organogram_position_id},function(data){
                // console.log(data);
                $('#editopid').val(data.id);
                $('#editopname').val(data.name);
                $('#editopuser_id').val(data.user_id);

                $('#editopp_id').val(data.p_id);
                $('#editopsp_id').val(data.sp_id);
                $('#editopcompany_organogram_level_id').val(data.company_organogram_level_id);

            });
            $('#editOrganogramPositionModal').modal();
        }
        function deleteOrganogramPosition(company_organogram_position_id){
            alertify.confirm('Are you sure you want to delete this Organogram_Position?', function () {


                $.get('{{ url('organograms/delete_organogram_position') }}',{company_organogram_position_id:company_organogram_position_id},function(data){
                    if (data=='success') {
                        toastr["success"]("Organogram Position deleted successfully",'Success');

                        location.reload();
                    }else{
                        toastr["error"]("Error deleting Organogram Position",'Success');
                    }

                });
            }, function () {
                alertify.error('Organogram Position not deleted');
            });
        }
        </script>

    @endsection
