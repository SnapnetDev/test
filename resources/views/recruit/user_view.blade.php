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
 .applied{
  background: #3d8b40;
color: #fff;
}
.applied:hover{
  background: ##f44336;
color: #fff;
}
.notapplied{
 background: #dddddd;
color: #8a8a8a;
}
.notapplied:hover{
   background: #3d8b40;
color: #fff;
}
</style>
  
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Job Details')}}</h1>
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
                @if($joblisting->status==1)
              	 <button id="favorite" type="button" class="btn {{Auth::user()->favorites->contains('job_listing_id', $joblisting->id)?'applied':'notapplied'}}  fav-btn waves-effect waves-light waves-round " >
                     {{Auth::user()->favorites->contains('job_listing_id', $joblisting->id)?'Remove Job from Favorites':'Add Job to Favorites'}}
                    </button>
                    @php
                          $date = new \Carbon\Carbon;

                        @endphp 
                        @if($date<$joblisting->expires)
                    <button id="apply" type="button" class="btn {{Auth::user()->applications->contains('job_listing_id', $joblisting->id)?'applied':'notapplied'}}  app-btn waves-effect waves-light waves-round " >
                      {{Auth::user()->applications->contains('job_listing_id', $joblisting->id)?'Cancel Job Application':'Apply for Job'}} 
                    </button>
                    @endif
                    @endif
              </div>
            </div>
            <div class="panel-body">
            	{{-- <div class="ribbon ribbon-clip ribbon-reverse ribbon-danger">
                        <span class="ribbon-inner"><a href="#" id="{{$joblisting->id}}" onclick="deleteJobListing(this.id)" style="color: #fff;"> Add Job to Favorites</a></span>
                      </div>
                      <div class="ribbon ribbon-clip ribbon-primary">
                        <span class="ribbon-inner"><a href="#" onclick="prepareEditData({{$joblisting->id}});" style="color: #fff;">Apply for Job</a></span>
                      </div> --}}
              
              
          
              
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
              <h3 class="panel-title">Skills Comparison</h3>
            </div>
            <div class="panel-body">
             <div class="example text-xs-center max-width">
                  <canvas id="skillChart" height="350"></canvas>
                </div>
            </div>
          </div>
         </div>

		</div> 

      	</div>
</div>
  <!-- End Page -->

   @include('recruit.modals.editJoblisting')
  
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
    $('.datepicker').datepicker({
    autoclose: true
});

     $('.enable_job').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });
 $('.fav-btn').on('click', function() {
      btn= $(this);
      
       $.get('{{ url('/recruits/emp_job_fav') }}',{ listing_id: {{$joblisting->id}} },function(data){
        if (data==1) {
          btn.removeClass('notapplied');
          btn.addClass('applied');
          btn.html('Remove Job from Favorites');
          toastr.success("Job Listing added to favorites",'Success');
         
        }
        if(data==2){
           btn.removeClass('applied');
         btn.addClass('notapplied');
         btn.html('Add Job to Favorites');
          toastr.warning("Job Listing removed from favorites",'Success');
        
        }
        
       });
    });
 $('.app-btn').on('click', function() {
      btn= $(this);
      
       $.get('{{ url('/recruits/emp_job_apply') }}',{ listing_id: {{$joblisting->id}} },function(data){
        if (data==1) {
          btn.removeClass('notapplied');
          btn.addClass('applied');
          btn.html('Cancel Job Application');
          toastr.success("Job Listing Application Successful",'Success');
          
        }
        if(data==2){
          btn.removeClass('applied');
         btn.addClass('notapplied');
         btn.html('Apply for Job');
          toastr.warning("Job Listing Application Cancelled",'Success');
         
        }
        
       });
    });


    });


  function deleteJobListing(listing_id){
    $.get('{{ url('/recruits/delete_job_listing') }}/',{ listing_id: listing_id },function(data){
      if (data=='success') {
    toastr.success("Job Listing deleted successfully",'Success');
      }else{
        toastr.error("Error deleting Salary Component",'Success');
      }
     
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
        },
         {
        label: "My Skills",
        pointRadius: 4,
        borderDashOffset: 2,
        backgroundColor: "rgba(250,122,122,0.25)",
        borderColor: "rgba(0,0,0,0)",
        pointBackgroundColor: Config.colors("red", 500),
        pointBorderColor: "#fff",
        pointHoverBackgroundColor: "#fff",
        pointHoverBorderColor: Config.colors("red", 500),
        data: [@foreach($joblisting->job->skills as $skill) 
                  @if(Auth::user()->skills->count()>0) 
                  @if(Auth::user()->skills->contains('id', $skill->id))
                    @foreach(Auth::user()->skills as $uskill) 
                      @if($skill->id==$uskill->id)
                      {{$uskill->pivot->competency->id}},
                       @endif
                     @endforeach
                     @else
                      0,
                     @endif
                   @endif 
               @endforeach]
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
  </script>
@endsection