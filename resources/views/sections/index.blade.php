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
      <h1 class="page-title">User Section</h1>
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
        <button class="btn btn-info " data-toggle="modal" data-target="#addSectionModal">Add Section</button>
            <button class="btn btn-info " data-toggle="modal" data-target="#addSectionMembersBulkModal">Add Bulk Section Members</button>
      </div>

        <div class="row">

          <div class="col-md-8">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="panel-group" id="exampleAccordionDefault" aria-multiselectable="true" role="tablist">


              @forelse($sections as $section)
                  <div class="panel">
                    <div class="panel-heading" id="exampleHeadingDefaultOne" role="tab">

                      <a class="panel-title  collapsed  " data-toggle="collapse" href="#section{{$section->id}}" data-parent="#exampleAccordionDefault" aria-expanded="false" aria-controls="section{{$section->id}}">

                     <strong>{{$section->name}}</strong>
                    </a>
                    </div>

                        <div class="panel-collapse collapse" id="section{{$section->id}}" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel" aria-expanded="false" style="height: 0px;">

                    <div class="panel-body">
                        <button class="btn btn-info" onclick="editSectionInfo({{$section->id}});"> Edit Section Info</button>
                        <button class="btn btn-info" onclick="deleteSectionInfo({{$section->id}});"> Delete Section Info</button>
                        <ul class="list-group list-group-bordered list-group-full" style="margin-top: 10px;">
                                <li class="list-group-item active" style="padding-left: 15px;"><i class="fa fa-info-circle"></i> Section Details</li>

                                <li class="list-group-item " style="padding-left: 15px;"><strong>Name:</strong> {{$section->name}}</li>
                                <li class="list-group-item " style="padding-left: 15px;"><strong>Other Name:</strong> {{$section->other_name}}</li>
                                <li class="list-group-item " style="padding-left: 15px;"><strong>Salary Project Code:</strong> {{$section->salary_project_code}}</li>
                                 <li class="list-group-item " style="padding-left: 15px;"><strong>Charge Project Code:</strong> {{$section->charge_project_code}}</li>


                              </ul>
                              <div class="panel panel-info " >
                                <div class="panel-heading">
                                  <h3 class="panel-title"><i class="fa fa-users"></i> Section Members</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped datatable">
                                      <thead>
                                        <tr>
                                        <th>Name</th>
                                        <th>Staff ID</th>
                                        <th>Salary Type</th>
                                        <th>Expatriate</th>
                                        <th></th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        @foreach ($section->users->where('status','!=',2) as $user)
                                      <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->emp_num}}</td>
                                        <td>{{$user->payroll_type}}</td>
                                        <td>{{$user->expat==1?"Yes":"No"}}</td>
                                        <td><button  class="pull-right btn btn-danger btn-sm" onclick="removeSectionMember({{$user->id}});"><i class="fa fa-trash">Remove Member</i></button></td>
                                      </tr>
                                      @endforeach
                                      </tbody>

                                    </table>
                                  </div>
                                </div>
                              {{-- <ul class="list-group list-group-bordered list-group-full">
                                <li class="list-group-item active" style="padding-left: 15px;">Section Members</li>
                                @foreach ($section->users as $user)
                                <li class="list-group-item " style="padding-left:15px; padding-right: 15px; line-height: 30px">{{$user->name}} <button  class="pull-right btn btn-danger btn-sm" onclick="removeSectionMember({{$user->id}});"><i class="fa fa-trash">Remove Member</i></button></li>
                                @endforeach

                              </ul> --}}


                      </div>
                    </div>
                  </div>

                  @empty
                  No Sections have been created
                @endforelse
                </div>


          </div>
          <div class="col-md-4">
              
                <div class="panel panel-info " style="text-align: justify;">
                        <div class="panel-heading">
                          <h3 class="panel-title"><i class="fa fa-users"></i> Employees Without Section</h3>
                          <div class="panel-actions">
                          <a href="{{url('/sections/download_section_non_members_template')}}" class="btn btn-info">Upload Template</a>
                          </div>
                        </div>
                        <div class="panel-body">
                            <br>
              <br>
                                <table class="table table-striped datatable">
                                        <thead>
                                          <tr>
                                          <th>Name</th>
                                          <th>Staff ID</th>
                                          <th>Expatriate</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($non_section_users as $nuser)
                                        <tr>
                                          <td>{{$nuser->name}}</td>
                                          <td>{{$nuser->emp_num}}</td>
                                        <td>{{$nuser->expat==1?"Yes":"No"}}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>

                                      </table>
                        </div>
                      </div>
              <div class="panel panel-info " style="text-align: justify;">
                <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-book"></i> Information</h3>
                </div>
                <div class="panel-body">
                  <p>To create a section click on the Add Section Button. Do not use already existing section names. When creating a section with a different dues formula but the same name,add a number as a differentiator. </p>
                  <p>To add members to the sections while adding them enter the first two letters of their name and their name will come up on the dropdown menu which can now be selected. You can add multiple members.</p>
                  <p>To import bulk users click on the Add Bulk Section Members, download the template, select the apprioporate sections for each member save the excel template and upload it.</p>
                  <p>Click on the name of the section to view its details and add and edit the section info, add and delete members.</p>

                </div>
              </div>
          </div>

          </div>
          </div>



      </div>
   @include('sections.modals.addSection')
     @include('sections.modals.editSection')
     @include('sections.modals.addSectionMembersBulk')
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
    return '{{url('sections')}}/search';
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
    return '{{url('sections')}}/search';
    }
    }
  });
  });
    $(function() {


    $(document).on('submit','#addSectionForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('sections.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
              if (data=='success') {
                toastr.success("Changes saved successfully",'Success');
               $('#addSectionModal').modal('toggle');
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
    $(document).on('submit','#editSectionForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('sections.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
 if (data=='success') {
                toastr.success("Changes saved successfully",'Success');
               $('#editSectionModal').modal('toggle');
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
    $(document).on('submit','#uploadSectionMembersBulkForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('sections.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
 if (data=='success') {
                toastr.success("Changes saved successfully",'Success');
               $('#addSectionMembersBulkModal').modal('toggle');
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
   function editSectionInfo(section_id){
    $.get('{{ url('/sections/section') }}/',{ section_id: section_id },function(data){
      console.log(data);
     $('#editname').val(data.name);
     $('#editother_name').val(data.other_name);
     $('#editsalary_project_code').val(data.salary_project_code);
     $('#editcharge_project_code').val(data.charge_project_code);
      $("#editmembers").find('option')
    .remove();



     jQuery.each( data.users, function( i, val ) {
       $("#editmembers").append($('<option>', {value:val.id, text:val.name,selected:'selected'}));
       // console.log(val.name);
              });
     $('#editsectionid').val(data.id);
    });
    $('#editSectionModal').modal();
  }

  function deleteSectionInfo(section_id){
    alertify.confirm('Are you sure you want to delete this section?', function () {
      $.get('{{ url('/sections/delete_section') }}/',{ section_id: section_id },function(data){
            if (data=='success') {
          toastr.success("Section deleted successfully",'Success');
          location.reload();
            }else{
              toastr.error("Error deleting Section",'Success');
            }

          });

 }, function () {
    alertify.error('Member not deleted');
  });

  }
  function removeSectionMember(user_id){
     alertify.confirm('Are you sure you want to remove this member?', function () {

           $.get('{{ url('/sections/remove_section_member') }}/',{ user_id: user_id },function(data){
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
