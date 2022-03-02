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
                            <h3 class="panel-title">Organograms</h3>
                            <div class="panel-actions">
                                <button class="btn btn-default" data-toggle="modal" data-target="#addOrganogramModal">Add Organogram</button>

                            </div>
                        </div>

                        <div class="panel-body">

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Manager</th>
                                    <th>Updated By</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($organograms as $organogram)
                                    <tr>
                                        <td>{{$organogram->name}}</td>
                                        <td>{{$organogram->manager->name}}</td>
                                        <td>{{$organogram->updater->name}}</td>
                                        <td>

                                            <a class="" href="{{url('organograms/organogram_positions?organogram_id='.$organogram->id)}}" title="View positions" class="btn btn-icon btn-info" id="{{$organogram->id}}" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            <a class="" title="edit" class="btn btn-icon btn-info" id="{{$organogram->id}}" onclick="prepareOrganogramEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$organogram->id}}" onclick="deleteOrganogram(this.id);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer">


                        </div>

                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading main-color-bg">
                            <h3 class="panel-title">Organogram Levels</h3>
                            <div class="panel-actions">
                                <button class="btn btn-default" data-toggle="modal" data-target="#addOrganogramLevelModal">Add Organogram Level</button>

                            </div>
                        </div>

                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Updated By</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($organogram_levels as $organogram_level)
                                    <tr>
                                        <td>{{$organogram_level->name}}</td>
                                        <td>{{$organogram_level->updater->name}}</td>
                                        <td><a class="" title="edit" class="btn btn-icon btn-info" id="{{$organogram_level->id}}" onclick="prepareOrganogramLevelEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$organogram_level->id}}" onclick="deleteOrganogramLevel(this.id);"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
    @include('organogram.modals.addorganogram')
    {{-- edit IP modal --}}
    @include('organogram.modals.editorganogram')
    @include('organogram.modals.addorganogramlevel')
    {{-- edit IP modal --}}
    @include('organogram.modals.editorganogramlevel')
@endsection
@section('scripts')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/raphael/raphael.js')}}"></script>
    <script>
        $(function() {

            $('#addOrganogramForm').submit(function(e){
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
                        $('#addOrganogramModal').modal('toggle');
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

            $('#editOrganogramForm').submit(function(e){
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
                        $('#editOrganogramModal').modal('toggle');
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
        function prepareOrganogramEditData(company_organogram_id){
            $.get('{{ url('/organograms/get_organogram') }}',{company_organogram_id:company_organogram_id},function(data){
                // console.log(data);
                $('#editoid').val(data.id);
                $('#editoname').val(data.name);
                $('#editomanager_id').val(data.manager_id);
            });
            $('#editOrganogramModal').modal();
        }
        function deleteOrganogram(company_organogram_id){
            alertify.confirm('Are you sure you want to delete this Organogram?', function () {


                $.get('{{ url('organograms/delete_organogram') }}',{company_organogram_id:company_organogram_id},function(data){
                    if (data=='success') {
                        toastr["success"]("Organogram deleted successfully",'Success');

                        location.reload();
                    }else{
                        toastr["error"]("Error deleting Organogram",'Success');
                    }

                });
            }, function () {
                alertify.error('Organogram not deleted');
            });
        }
        $(function() {

            $('#addOrganogramLevelForm').submit(function(e){
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
                        $('#addOrganogramLevelModal').modal('toggle');
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

            $('#editOrganogramLevelForm').submit(function(e){
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
                        $('#editOrganogramLevelModal').modal('toggle');
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
        function prepareOrganogramLevelEditData(company_organogram_level_id){
            $.get('{{ url('/organograms/get_organogram_level') }}',{company_organogram_level_id:company_organogram_level_id},function(data){
                // console.log(data);
                $('#editolid').val(data.id);
                $('#editolname').val(data.name);
            });
            $('#editOrganogramLevelModal').modal();
        }
        function deleteOrganogramLevel(company_organogram_level_id){
            alertify.confirm('Are you sure you want to delete this Organogram Level?', function () {


                $.get('{{ url('organograms/delete_organogram_level') }}',{company_organogram_level_id:company_organogram_level_id},function(data){
                    if (data=='success') {
                        toastr["success"]("Organogram Level deleted successfully",'Success');

                        location.reload();
                    }else{
                        toastr["error"]("Error deleting Organogram Level",'Success');
                    }

                });
            }, function () {
                alertify.error('Organogram Level not deleted');
            });
        }
    </script>
@endsection
