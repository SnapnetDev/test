@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
 <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
 <link rel="stylesheet" href="{{ asset('global/vendor/morris/morris.css')}}">
 <link rel="stylesheet" href="{{ asset('assets/examples/css/apps/work.css')}}">
<link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
  <link rel="stylesheet" href="{{ asset('global/vendor/summernote/summernote.css') }}">
   <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
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
  		<h1 class="page-title">{{__('Jobs Vacancies Applied For')}}</h1>
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
      <div class="btn-group btn-group-justified">
                    <div class="btn-group" role="group">
                      <a href="{{url('recruits/myjobs')}}" class="btn btn-primary waves-effect waves-light waves-round">
                      
                        <span class="text-uppercase hidden-sm-down">All Jobs</span>
                      </a>
                    </div>
                    <div class="btn-group" role="group">
                      <a href="{{url('recruits/jobsapplied')}}" class="btn btn-success  waves-effect waves-light waves-round">
                        
                        <span class="text-uppercase hidden-sm-down">Jobs Applied For</span>
                      </a>
                    </div>
                    <div class="btn-group" role="group">
                      <a href="{{url('recruits/favjobs')}}" class="btn btn-info  waves-effect waves-light waves-round">
                     
                        <span class="text-uppercase hidden-sm-down">Favorite Jobs</span>
                      </a>
                    </div>
                  </div>
                  <br>
     <div class="row">
        <div class="col-md-8">
            @forelse($joblistings as $joblisting)

             @if(Auth::user()->applications->contains('job_listing_id', $joblisting->id))
            <div class="panel panel-bordered">
                <div class="panel-heading">
                  <h3 class="panel-title">{{$joblisting->job?$joblisting->job->title:''}} - {{ $joblisting->job->department->name }} - {{ $joblisting->job->department->company->name }}</h3>
                  <div class="panel-actions">
                    <a  href="{{url('recruits/view_job_listing').'listing_id='.$joblisting->id}}" class="btn btn-info  waves-effect waves-light waves-round " >
                         View Job Details
                        </a>
                        @if($joblisting->status==1)
                        <button id="{{ $joblisting->id}}" type="button" class="btn {{Auth::user()->favorites->contains('job_listing_id', $joblisting->id)?'applied':'notapplied'}}  fav-btn waves-effect waves-light waves-round " >
                         {{Auth::user()->favorites->contains('job_listing_id', $joblisting->id)?'Remove Job from Favorites':'Add Job to Favorites'}}
                        </button>
                        @php
                          $date = new \Carbon\Carbon;

                        @endphp 
                        @if($date<$joblisting->expires)
                        <button id="{{ $joblisting->id}}" type="button" class="btn {{Auth::user()->applications->contains('job_listing_id', $joblisting->id)?'applied':'notapplied'}}  app-btn waves-effect waves-light waves-round " >
                          {{Auth::user()->applications->contains('job_listing_id', $joblisting->id)?'Cancel Job Application':'Apply for Job'}} 
                        </button>
                        @endif
                        @endif
                  </div>
                </div>
                  
                 <ul class="list-group list-group-bordered list-group-full">
                    <li class="list-group-item bg-grey-300">Description</li>
                  <li class="list-group-item ">
                    {!! $joblisting->job->description !!}
                  </li>
                </ul>
                <ul class="list-group list-group-bordered list-group-full">
                    <li class="list-group-item bg-grey-300">Expires</li>
                  <li class="list-group-item ">
                     {{date("F j, Y",strtotime($joblisting->expires))}}({{\Carbon\Carbon::parse($joblisting->expires)->diffForHumans()}})
                  </li>
                </ul>
              </div>
              @endif
              @empty
              <div class="alert dark alert-primary" role="alert">
                  <h4>No Jobs</h4>
                  <p>
                   Your have not applied for any jobs
                  </p>
                </div>
              @endforelse
               {!! $joblistings->appends(Request::capture()->except('page'))->render() !!}
        </div>
        <div class="col-md-4">
                 <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Filters</h3>
                
              </div>
              <form class="" action="{{url('recruits/myjobs')}}" method="get" >


              <div class="panel-body">
                <div class="form-group">
                  <label for="">Job Title Contains</label>

                  <input type="text" name="name_contains" class="form-control col-lg-6" id="email_t" placeholder="" value="{{ request()->name_contains }}">

                </div>
                <div class="form-group">
                  <label for="">Departments</label>
                  <select class="select2 form-control" name="deptftype">
                    <option value="or">OR</option>
                    <option value="and">AND</option>
                  </select>
                  <select id="role_f" class=" select2 form-control col-lg-6" name="department[]" multiple>
                    @forelse ($departments as $department)
                      <option value="{{$department->id}}" >{{$department->name}}</option>
                    @empty
                      <option value="">No Departments Created</option>
                    @endforelse
                  </select>


                </div>
                <div class="form-group">
                  <label for="">Created At</label>
                  <div class="input-daterange input-group" id="datepicker">
                    <input type="text" class="input-sm form-control" name="created_from" placeholder="From date" value="{{ request()->created_from }}"/>
                    <span class="input-group-addon">to</span>
                    <input type="text" class="input-sm form-control" name="created_to" placeholder="To date" value="{{ request()->created_to }}"/>
                </div>
                </div>
                <div class="form-group">
                  <label for="">Updated At</label>
                  <div class="input-daterange input-group" id="datepicker">
                    <input type="text" class="input-sm form-control" name="created_from" placeholder="From date" value="{{ request()->updated_from }}"/>
                    <span class="input-group-addon">to</span>
                    <input type="text" class="input-sm form-control" name="created_to" placeholder="To date" value="{{ request()->updated_to }}"/>
                </div>
                </div>
                <button type="submit" class="btn btn-info" >Filter</button>
                <button type="reset" class="btn btn-warning pull-right" >Clear Filters</button>
              </div>
              </form>
              </div>
        </div>
      </div>
        
    </div> 
		</div> 

      	</div>
</div>
  
  
@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>

  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
  <script src="{{ asset('global/vendor/raphael/raphael-min.js')}}"></script>
  <script src="{{ asset('global/vendor/morris/morris.min.js')}}"></script>
<script src="{{asset('global/vendor/summernote/summernote.min.js')}}"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
  <script type="text/javascript">
  	  $(document).ready(function() {
        $('#requirements').summernote();
         $('.select2').select2();
         $('.input-daterange').datepicker({
    autoclose: true
});
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
      listing_id=$(this).attr('id');
      
       $.get('{{ url('/recruits/emp_job_fav') }}',{ listing_id: listing_id },function(data){
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
      listing_id=$(this).attr('id');
      
       $.get('{{ url('/recruits/emp_job_apply') }}',{ listing_id: listing_id },function(data){
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


   

    $(document).on('submit','#addSalaryComponentForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('payrollsettings.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

                toastr.success("Changes saved successfully",'Success');
               $('#addSalaryComponentModal').modal('toggle');
          $( "#ldr" ).load('{{url('payrollsettings/salary_components')}}');

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
  </script>
@endsection