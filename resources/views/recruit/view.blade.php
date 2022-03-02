@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
 <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
 <link rel="stylesheet" href="{{ asset('global/vendor/morris/morris.css')}}">
 <link rel="stylesheet" href="{{ asset('assets/examples/css/apps/work.css')}}">
<link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
  <link rel="stylesheet" href="{{ asset('global/vendor/summernote/summernote.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/examples/css/charts/chartjs.css') }}">
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <style type="text/css">
     #selectable .ui-selecting { background: #FECA40; }
  #selectable .ui-selected { background: #F39814; color: white; }
   </style>
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Recruit')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('Recruit')}}</li>
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
    
		<div class="page-content">
      <div class="row">
      <div class="col-md-7">
      	<div class="panel panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title">{{$joblisting->job?$joblisting->job->title:''}}</h3>
              <div class="panel-actions">
              	<input type="checkbox" class="active-toggle enable_job" id="{{$joblisting->id}}" {{$joblisting->status == 1?'checked':''}} >
              </div>
            </div>
            <div class="panel-body">
            	<div class="ribbon ribbon-clip ribbon-reverse ribbon-danger">
                        <span class="ribbon-inner"><a href="#" id="{{$joblisting->id}}" onclick="deleteJobListing(this.id)" style="color: #fff;">Delete</a></span>
                      </div>
                      <div class="ribbon ribbon-clip ribbon-primary">
                        <span class="ribbon-inner"><a href="#" onclick="prepareEditData({{$joblisting->id}});" style="color: #fff;">Edit</a></span>
                      </div>
               
            </div>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Company</li>
              <li class="list-group-item ">
                {!! $joblisting->job->department->company->name !!}
              </li>
            </ul>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Department</li>
              <li class="list-group-item ">
                {!! $joblisting->job->department->name !!}
              </li>
            </ul>
             <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Description</li>
              <li class="list-group-item ">
                {!! $joblisting->job->description !!}
              </li>
            </ul>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Minimum Educational Qualification</li>
              <li class="list-group-item ">
                {{$joblisting->job->qualification?$joblisting->job->qualification->name:''}}
              </li>
            </ul>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Expires</li>
              <li class="list-group-item ">
                 {{date("F j, Y",strtotime($joblisting->expires))}}({{\Carbon\Carbon::parse($joblisting->expires)->diffForHumans()}})
              </li>
            </ul>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Salary</li>
              <li class="list-group-item ">
                 {{$joblisting->salary_from}} - {{$joblisting->salary_to}}
              </li>
            </ul>
             <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Experience</li>
              <li class="list-group-item ">
                 {{$joblisting->experience_from}} - {{$joblisting->experience_to}} Years
              </li>
            </ul>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Level</li>
              <li class="list-group-item ">
                @if($joblisting->level==1)Graduate Trainee @elseif($joblisting->level==2)Entry Level @elseif($joblisting->level==3)Non- Manager @elseif($joblisting->level==4) Manager @endif
              </li>
            </ul>
             <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Extra Requirements</li>
              <li class="list-group-item ">
                {!! $joblisting->requirements !!}
              </li>
             </ul>
             @if($joblisting->job->skills)
               <ul class="list-group list-group-bordered list-group-full">
                @php
                  $sn=1;
                @endphp
                 
               <li class="list-group-item bg-grey-300">Skills</li>
               @foreach($joblisting->job->skills as $skill)
                <li class="list-group-item ">{{$sn}}.  {{strtoupper($skill->name)}} - {{$skill->pivot->competency->proficiency}}</li>
              @php
                $sn++;
              @endphp
              @endforeach
               </ul>
              @endif
          </div>
         </div>
         <div class="col-md-5">
           <div class="panel panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title">Skills</h3>
            </div>
            <div class="panel-body">
             <div class="example text-xs-center max-width">
                  <canvas id="skillChart" height="200"></canvas>
                </div>
            </div>
          </div>
          <div class="panel panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title">Applicants</h3>
            </div>
            
               @if($joblisting->jobapplications)
               <ul  class="list-group list-group-bordered list-group-full">
                @php
                  $sn=1;
                @endphp
                 
              
               @foreach($joblisting->jobapplications as $application)
                <li id="{{$application->applicable->id}}" class="list-group-item ">{{$sn}}.  {{strtoupper($application->applicable->name)}} <button class="btn btn-info pull-right btn-sm" onclick="viewApplicantSummary({{$application->applicable->id}},{{$joblisting->id}});">View Summary</button></li>
              @php
                $sn++;
              @endphp
              @endforeach
               </ul>
              @endif
            
          </div>
         </div>

		</div> 

      	</div>
</div>
  <!-- End Page -->

   @include('recruit.modals.editJoblisting')
   <div class="modal fade " id="appSummaryModal" aria-labelledby="exampleModalTabs" role="dialog" tabindex="-1" aria-hidden="true" style="display: none;">
     </div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>

  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script src="{{ asset('global/vendor/chart-js/Chart.js')}}"></script>
  <script src="{{ asset('global/vendor/raphael/raphael-min.js')}}"></script>
  <script src="{{ asset('global/vendor/morris/morris.min.js')}}"></script>
<script src="{{asset('global/vendor/summernote/summernote.min.js')}}"></script>
  <script type="text/javascript">
  	  $(document).ready(function() {
        $('#requirements').summernote();
        $( "#selectable" ).selectable();
    $('.datepicker').datepicker({
    autoclose: true
});

     $('.enable_job').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });
 $('.enable_job').on('change', function() {
      listing_id= $(this).attr('id');
      
       $.get('{{ url('/recruits/change_listing_status') }}/',{ listing_id: listing_id },function(data){
        if (data==1) {
          toastr.success("Job Listing is now Enabled",'Success');
        }
        if(data==2){
          toastr.warning("Job Listing is Disabled",'Success');
        }
        
       });
    });

    $(document).on('submit','#editJobListingForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('recruits.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

                toastr.success("Changes saved successfully",'Success');
               $('#editJobListingModal').modal('toggle');
         

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

      function viewApplicantSummary(user_id,listing_id)
      {
        
            $("#appSummaryModal").load('{{ url('/recruits/applicant_summary') }}/?user_id='+user_id+'&listing_id='+listing_id);
          $('#appSummaryModal').modal();
        
      }

      function departmentChange(department_id){
    event.preventDefault();
    $.get('{{ url('/users/department/jobroles') }}/'+department_id,function(data){
      
      
      if (data.jobs=='') {
         $("#jobroles").empty();
        $('#jobroles').append($('<option>', {value:0, text:'Please Create a Jobrole in Department'}));
      }else{
        $("#jobroles").empty();
        jQuery.each( data.jobroles, function( i, val ) {       
               $('#jobroles').append($('<option>', {value:val.id, text:val.title}));  
              });
      }
      
     });
  }

  function deleteJobListing(listing_id){
    $.get('{{ url('/recruits/delete_job_listing') }}/',{ listing_id: listing_id },function(data){
      if (data=='success') {
    toastr.success("Job Listing deleted successfully",'Success');
      }else{
        toastr.error("Error deleting Salary Component",'Success');
      }
     
    });
  }
       function prepareEditData(listing_id){
    $.get('{{ url('/recruits/get_job_listing_info') }}/',{ listing_id: listing_id },function(data){
     
     $('#editjtype').val(data.type);
     $('#editjlevel').val(data.level);
     $('#editjsalary_from').val(data.salary_from);
     $('#editjsalary_to').val(data.salary_to);
      $('#editjexperience_from').val(data.experience_from);
       $('#editjexperience_to').val(data.experience_to);
       $('#editjexpires').val(data.expires);
        $('#editjrequirements').val(data.requirements);
        $('#editjlid').val(data.id);
        $('#editjobid').val(data.job_id);
       
    $('#editJobListingModal').modal();
  });
  }

  var ctx = document.getElementById('skillChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'radar',

    // The data for our dataset
    data: {
        labels: [  @foreach($joblisting->job->skills as $skill)"{{strtoupper($skill->name)}}", @endforeach],
        pointLabelFontSize: 14,
        datasets: [{
            label: "Skill for Job",
             pointRadius: 4,
        borderDashOffset: 2,
            backgroundColor: 'rgba(98, 168, 234, 0.15)',
            borderColor: 'rgba(0, 0, 0,0)',
             pointBackgroundColor: Config.colors("primary", 600),
        pointBorderColor: "#fff",
        pointHoverBackgroundColor: "#fff",
        pointHoverBorderColor: Config.colors("primary", 600),
            data: [ @foreach($joblisting->job->skills as $skill){{$skill->pivot->competency->id}}, @endforeach],
        }]
    },

    // Configuration options go here
    options: {
      responsive: true,
        scale: {
          ticks: {
            beginAtZero: true
          }
        }
    }
});

 $(function() {
           $( "#selectable" ).selectable({
      stop: function() {
       
        $( ".ui-selected", this ).each(function() {
         console.log($(this).attr('id'));
        });
      }
    });
         });
  </script>
@endsection