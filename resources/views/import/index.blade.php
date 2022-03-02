@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
  <style media="screen">
    .form-cont{
      border: 1px solid #cccccc;
      padding: 10px;
      border-radius: 5px;
    }
    #stgcont {
      list-style: none;
    }
    #stgcont li{
      margin-bottom: 10px;
    }
  </style>

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Import Employees</h1>
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
          @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                        {{ session('success') }}
                    </div>
                @endif
              @php
                      $import_types=[
                      'employees'=>['title'=>'Import Employee Data','description'=>'Import the data of your staff and their next of kin',
                      'template_url'=>'download_employee_template',
                      'upload_url'=>'employees','modal_title'=>'Upload Employee Data'],

                      'job_roles'=>['title'=>'Import Job roles','description'=>'Import Job Roles in your organization',
                      'template_url'=>'download_jobroles_template',
                      'upload_url'=>'jobroles','modal_title'=>'Upload Job Roles Data'],

                      'departments'=>['title'=>'Import Departments','description'=>'Import Departments in your organization',
                      'template_url'=>'download_department_template',
                      'upload_url'=>'departments','modal_title'=>'Upload Departments'],

                      'branches'=>['title'=>'Import Branches','description'=>'Import Branches in your organization',
                      'template_url'=>'download_branches_template',
                      'upload_url'=>'branches','modal_title'=>'Upload Branches'],

                      'grades'=>['title'=>'Import Grades','description'=>'Import the grades in your organization',
                      'template_url'=>'download_grades_template',
                      'upload_url'=>'grades','modal_title'=>'Upload Grades'],

                      'dependents'=>['title'=>'Import Employee Dependent','description'=>'Import Employee Dependents in your organization',
                      'template_url'=>'download_dependent_template',
                      'upload_url'=>'dependents','modal_title'=>'Upload Employee Dependents'],

                      'academic_qualifications'=>['title'=>'Import Employee Academic Qualification','description'=>'Import the Academic qualification of our staff',
                      'template_url'=>'download_academic_qualification_template',
                      'upload_url'=>'academic_qualifications','modal_title'=>'Upload Employee Academic Qualifications'],

                      'work_experience'=>['title'=>'Import Employee Work Experience','description'=>'Import Employee Work Experience in your organization',
                      'template_url'=>'download_work_experience_template',
                      'upload_url'=>'work_experience','modal_title'=>'Upload Employee Work Experience'],

                      'employee_supervisors'=>['title'=>'Import Reporting Lines','description'=>'Import Reporting line in your organization',
                      'template_url'=>'download_reporting_line_template',
                      'upload_url'=>'employee_supervisors','modal_title'=>'Upload Reporting Line'],

                      

                      ];
                      @endphp
              <div class="row">
                  @foreach( $import_types as $type)

                  <div class="col-md-4">
                      <div class="panel panel-info panel-line" >
                          <div class="panel-heading main-color-bg">
                              <h3 class="panel-title">{{$type['title']}}</h3>
                              <div class="panel-actions">
                                  <button class=" btn-primary btn"onclick="showUploadModal('{{$type['upload_url']}}','{{$type['modal_title']}}')">Upload </button>
                              </div>
                          </div>
                          <div class="panel-body">
                              <p class="list-group-item-text">{{$type['description']}} <br>
                                  <a href="{{url('import/'.$type['template_url'])}}">Download Template</a>
                              </p>
                          </div>
                      </div>
                  </div>
@endforeach


              </div>



  </div>
</div>
@include('import.modals.upload_employees')
@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
 <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {

     $('.datepicker').datepicker({
    autoclose: true,
    format:'yyyy-mm',
     viewMode: "months",
    minViewMode: "months"
});
});
$(document).on('submit', '.importForm', function(event) {
      event.preventDefault();
    var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          // console.log(formdata);
          // return;
          //var formAction = form.attr('action');
          $.ajax({
              url         : '{{url('import')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){
                  // toastr["success"]("Loan Requested successfully",'Success');
                  if (data=='success'){
                    toastr.success("Import was successful",'Success');

                  }else{
                    toastr.error("Import was not successful",'Error');
                  }
              },
              error:function(data, textStatus, jqXHR){
                  // toastr["success"]("Loan Requested successfully",'Success');

              }
          });

    });

function showUploadModal(import_type,name){
    $(document).ready(function () {
        $('#upload_type').val(import_type);
        $('#action_name').html(name);
        $('#UploadModal').modal();
    });
}
</script>

@endsection
