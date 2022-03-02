@extends('layouts.master')
@section('stylesheets')
  {{-- <link rel="stylesheet" href="{{ asset('datatables/DataTables-1.10.16/css/dataTables.bootstrap.min.css')}}"> --}}
  <link rel="stylesheet" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
      <style type="text/css">
        .list-group-full .list-group-bordered .list-group-item {
    padding-left: 15px;
}
      </style>
@endsection
@section('content')
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">User Unions</h1>
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
      <div style="margin-bottom: 15px;">
        <button class="btn btn-info " data-toggle="modal" data-target="#addUnionModal">Add Union</button>
            <button class="btn btn-info " data-toggle="modal" data-target="#addUnionMembersBulkModal">Add Bulk Union Members</button>
      </div>
       
        <div class="row">

          <div class="col-md-9">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif
           
            <div class="panel-group" id="exampleAccordionDefault" aria-multiselectable="true" role="tablist">
              @forelse($unions as $union)
                  <div class="panel">
                    <div class="panel-heading" id="exampleHeadingDefaultOne" role="tab">
                      
                      <a class="panel-title  collapsed  " data-toggle="collapse" href="#union{{$union->id}}" data-parent="#exampleAccordionDefault" aria-expanded="false" aria-controls="union{{$union->id}}">
                       
                     <strong>{{$union->name}}</strong> 
                    </a>
                    </div>
                    
                        <div class="panel-collapse collapse" id="union{{$union->id}}" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel" aria-expanded="false" style="height: 0px;">
                  
                    <div class="panel-body">
                        <button class="btn btn-info" onclick="editUnionInfo({{$union->id}});"> Edit Union Info</button>
                        <button class="btn btn-info" onclick="deleteUnionInfo({{$union->id}});"> Delete Union Info</button>
                        <ul class="list-group list-group-bordered list-group-full" style="margin-top: 10px;">
                                <li class="list-group-item active" style="padding-left: 15px;"><i class="fa fa-info-circle"></i> Union Details</li> 
                                
                                <li class="list-group-item " style="padding-left: 15px;"><strong>Name:</strong> {{$union->name}}</li> 
                                <li class="list-group-item " style="padding-left: 15px;"><strong>Dues Formula:</strong> {{$union->dues_formula}}</li> 
                                

                              </ul>
                              <div class="panel panel-info " >
                                <div class="panel-heading">
                                  <h3 class="panel-title"><i class="fa fa-users"></i> Union Members</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped datatable">
                                      <thead>
                                        <tr>
                                        <th>Name</th>
                                        <th>Staff ID</th>
                                        <th>Salary Type</th>
                                        <th></th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        @foreach ($union->users as $user)
                                      <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->emp_num}}</td>
                                        <td>{{$user->payroll_type}}</td>
                                        <td><button  class="pull-right btn btn-danger btn-sm" onclick="removeUnionMember({{$user->id}});"><i class="fa fa-trash">Remove Member</i></button></td>
                                      </tr>
                                      @endforeach
                                      </tbody>
                                       
                                    </table>
                                  </div>
                                </div>
                              {{-- <ul class="list-group list-group-bordered list-group-full">
                                <li class="list-group-item active" style="padding-left: 15px;">Union Members</li> 
                                @foreach ($union->users as $user)
                                <li class="list-group-item " style="padding-left:15px; padding-right: 15px; line-height: 30px">{{$user->name}} <button  class="pull-right btn btn-danger btn-sm" onclick="removeUnionMember({{$user->id}});"><i class="fa fa-trash">Remove Member</i></button></li> 
                                @endforeach

                              </ul> --}}
                           
                       
                      </div>
                    </div>
                  </div>

                  @empty
                  No Unions have been created
                @endforelse
                </div>


          </div>
          <div class="col-md-3">
              <div class="panel panel-info " style="text-align: justify;">
                <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-book"></i> Information</h3>
                </div>
                <div class="panel-body">
                  <p>To create a union click on the Add Union Button. Do not use already existing union names. When creating a union with a different dues formula but the same name,add a number as a differentiator. </p>
                  <p>To add members to the unions while adding them enter the first two letters of their name and their name will come up on the dropdown menu which can now be selected. You can add multiple members.</p>
                  <p>To import bulk users click on the Add Bulk Union Members, download the template, select the apprioporate unions for each member save the excel template and upload it.</p>
                  <p>Click on the name of the union to view its details and add and edit the union info, add and delete members.</p>
                  <p >To use the formula to determine the dues of a particular union, constants for salary components are used. By default we have the <code>basic_salary</code> and <code>gross_salary</code>. Other user defined constants can be found below.</p>
                  <ul class="list-group list-group-dividered list-group-full">
                     <li class="list-group-item ">Project Salary Constants</li>
                    @foreach($project_components as $pc)
                     @if ($loop->last)
                         and <code>{{$pc->constant}}</code>.
                      @else
                      <code>{{$pc->constant}}</code>,
                      @endif
                     
                    @endforeach

                  
                </ul>

                <ul class="list-group list-group-dividered list-group-full">
                    <li class="list-group-item ">Office Salary Constants</li>
                    @foreach($office_components as $oc)
                     @if ($loop->last)
                         and <code>{{$oc->constant}}</code>.
                     @else
                         <code>{{$oc->constant}}</code>,
                      @endif
                     
                    @endforeach
                  
                </ul>
                </div>
              </div>
          </div>
            
          </div>
          </div>



      </div>
   @include('unions.modals.addUnion')
     @include('unions.modals.editUnion')
     @include('unions.modals.addUnionMembersBulk')
@endsection
@section('scripts')
  {{-- <script type="text/javascript" src="{{ asset('datatables/DataTables-1.10.16/js/dataTables.bootstrap.min.js')}}"></script> --}}
  <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
   <script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
  <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

  <script type="text/javascript">
  $(document).ready(function() {
     $('.datatable').DataTable();
    $('.select2').select2();
    $('.input-daterange').datepicker({
    autoclose: true
});



} );
   $(function(){

  $('#members').select2({
     ajax: {
     delay: 250,
     processResults: function (data) {
          return {        
    results: data
      };
    },
    url: function (params) {
    return '{{url('unions')}}/search';
    } 
    }
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
    return '{{url('unions')}}/search';
    } 
    }
  });
  });
    $(function() {
  

    $(document).on('submit','#addUnionForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('unions.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
              if (data=='success') {
                toastr.success("Changes saved successfully",'Success');
               $('#addUnionModal').modal('toggle');
          {{-- $( "#ldr" ).load('{{url('payrollsettings/salary_components')}}'); --}}
          location.reload();
              }
                

            },
            error:function(data, textStatus, jqXHR){
               jQuery.each( data['responseJSON'], function( i, val ) {
                jQuery.each( val, function( i, valchild ) {
                toastr.error(valchild[0]);
              });  
              });
            }
        });
      
    });
  });
    $(function() {
    $(document).on('submit','#editUnionForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('unions.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
 if (data=='success') {
                toastr.success("Changes saved successfully",'Success');
               $('#editUnionModal').modal('toggle');
          {{-- $( "#ldr" ).load('{{url('payrollsettings/salary_components')}}'); --}}
          location.reload();
              }
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
    $(function() {
    $(document).on('submit','#uploadUnionMembersBulkForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('unions.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
 if (data=='success') {
                toastr.success("Changes saved successfully",'Success');
               $('#addUnionMembersBulkModal').modal('toggle');
          {{-- $( "#ldr" ).load('{{url('payrollsettings/salary_components')}}'); --}}
          location.reload();
              }
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
   function editUnionInfo(union_id){
    $.get('{{ url('/unions/union') }}/',{ union_id: union_id },function(data){
      console.log(data);
     $('#editname').val(data.name);
     $('#editformula').val(data.dues_formula);
      $("#editmembers").find('option')
    .remove();
    
   
    
     jQuery.each( data.users, function( i, val ) {
       $("#editmembers").append($('<option>', {value:val.id, text:val.name,selected:'selected'}));
       // console.log(val.name);
              }); 
     $('#editunionid').val(data.id);
    });
    $('#editUnionModal').modal();
  }

  function deleteUnionInfo(union_id){
    alertify.confirm('Are you sure you want to delete this union?', function () {
      $.get('{{ url('/unions/delete_union') }}/',{ union_id: union_id },function(data){
            if (data=='success') {
          toastr.success("Union deleted successfully",'Success');
          location.reload();
            }else{
              toastr.error("Error deleting Union",'Success');
            }
           
          });

 }, function () {
    alertify.error('Member not deleted');
  });
    
  }
  function removeUnionMember(user_id){
     alertify.confirm('Are you sure you want to remove this member?', function () {

           $.get('{{ url('/unions/remove_union_member') }}/',{ user_id: user_id },function(data){
          if (data=='success') {
        toastr.success("Member removed successfully",'Success');
        location.reload();
          }else{
            toastr.error("Error removing Member",'Success');
          }
         
        });
      }, function () {
          alertify.error('Member not deleted');
        });
   
  }
  </script>


@endsection
