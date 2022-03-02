@extends('layouts.master')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
 <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<style type="text/css">
  a.list-group-item:hover {
    text-decoration: none;
    background-color: #3f51b5;
}
</style>
@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Project Management</h1>
      
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
      <div class="col-md-4" id="details">
        
      </div>
      <div class="col-md-4" id="members">
        
      </div>
      <div class="col-md-4" id="tasks">
        
      </div>
    </div>
    
      
      
      
  </div>
  </div>
  <!-- Site Action -->
 @include('project_management.modals.addMember')
 @include('project_management.modals.editProjectDetails')
    
  
  <!-- End Add User Form -->

@section('scripts')
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
   <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
   <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"> </script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#emptable').DataTable();
    $( "#tasks" ).load( "{{url('projects/project_tasks')}}?project_id={{$project->id}}");
    $( "#members" ).load( "{{url('projects/project_members')}}?project_id={{$project->id}}");
    $( "#details" ).load( "{{url('projects/project_details')}}?project_id={{$project->id}}");
} );
$(function() {
    $(document).on('submit','#addMemberForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('projects.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

              console.log(data);
                toastr.success("Members Changed successfully",'Success');
                $('#addMemberModal').modal('toggle');
           $( "#members" ).load( "{{url('projects/project_members')}}?project_id={{$project->id}}");
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
    $(document).on('submit','#editProjectDetailsForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('projects.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

              console.log(data);
                toastr.success("Members Changed successfully",'Success');
                $('#addMemberModal').modal('toggle');
           $( "#members" ).load( "{{url('projects/project_details')}}?project_id={{$project->id}}");
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
    $(document).on('submit','#editProjectTaskForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('projects.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

              console.log(data);
                toastr.success("Members Changed successfully",'Success');
                $('#addMemberModal').modal('toggle');
           $( "#members" ).load( "{{url('projects/project_tasks')}}?project_id={{$project->id}}");
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
    $(document).on('submit','#addProjectTaskForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('projects.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

              console.log(data);
                toastr.success("Members Changed successfully",'Success');
                $('#addMemberModal').modal('toggle');
           $( "#members" ).load( "{{url('projects/project_tasks')}}?project_id={{$project->id}}");
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

    $('#editmembers').select2({
     ajax: {
     delay: 250,
     processResults: function (data) {
          return {        
    results: data
      };
    },
    url: function (params) {
    return '{{url('users')}}/search';
    } 
    }
  });
  });
  
    function prepareEditMData(project_id){
    $.get('{{ url('/projects/get_project_members') }}/',{ project_id: project_id },function(data){
      
     
      $("#editmembers").find('option')
    .remove();
    
    
     jQuery.each( data.members, function( i, val ) {
       $("#editmembers").append($('<option>', {value:val.id, text:val.name,selected:'selected'}));
       // console.log(val.name);
              }); 
     $('#editmprojectid').val(data.project_id);
    });
    $('#addMemberModal').modal();
  }
  function prepareEditPData(project_id){
    $.get('{{ url('/projects/get_project') }}/',{ project_id: project_id },function(data){
      
     
     $('#editmprojectid').val(data.project_id);
    });
    $('#editProjectDetailsModal').modal();
  }
  $(function(){

  $('#emps').select2({
    placeholder: "Employee Name",
     multiple: false,
    id: function(bond){ return bond._id; },
     ajax: {
     delay: 250,
     processResults: function (data) {
          return {        
    results: data
      };
    },
    url: function (params) {
    return '{{url('users')}}/search';
    } 
    }
    
  });
  
  });
</script>
@endsection