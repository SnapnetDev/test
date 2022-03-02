@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
 <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
 <link rel="stylesheet" href="{{ asset('global/vendor/morris/morris.css')}}">
 <link rel="stylesheet" href="{{ asset('assets/examples/css/apps/work.css')}}">
<link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
  <link rel="stylesheet" href="{{ asset('global/vendor/summernote/summernote.css') }}">
   <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Recruit')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('Recruit')}}  (Internal Jobs)</li>
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
                      <a href="{{url('recruits')}}" class="btn btn-primary waves-effect waves-light waves-round">
                      
                        <span class="text-uppercase hidden-sm-down">Internal Jobs</span>
                      </a>
                    </div>
                    <div class="btn-group" role="group">
                      <a href="{{url('recruits/external')}}" class="btn btn-success  waves-effect waves-light waves-round">
                        
                        <span class="text-uppercase hidden-sm-down">External Jobs</span>
                      </a>
                    </div>

                  </div>
                  <br>
                  <br>
       <div class="row">
        <div class="col-md-8">
      @forelse($joblistings as $joblisting)
      	<div class="panel panel-info panel-line panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title">{{$joblisting->job?$joblisting->job->title:''}}</h3>
              <div class="panel-actions">
                <button id="{{ $joblisting->id}}" type="button" class="btn btn-info  fav-btn waves-effect waves-light waves-round " onclick="prepareEditData(this.id)">
                        Edit Job Listing
                        </button>
              	<input type="checkbox" class="active-toggle enable_job" id="{{$joblisting->id}}" {{$joblisting->status == 1?'checked':''}} >
              </div>
            </div>
            <div class="panel-body">
            	<div class="ribbon ribbon-clip ribbon-reverse ribbon-danger">
                        <span class="ribbon-inner"><a href="#" id="{{$joblisting->id}}" onclick="deleteJobListing(this.id)" style="color: #fff;">Delete</a></span>
                      </div>
                      <div class="ribbon ribbon-clip ribbon-primary">
                        <span class="ribbon-inner"><a href="{{url('recruits/view_job_listing').'?listing_id='.$joblisting->id}}" style="color: #fff;">View</a></span>
                      </div>
              
            </div>
            <ul class="list-group list-group-bordered list-group-full">
                    <li class="list-group-item bg-grey-300">Description</li>
                  <li class="list-group-item ">
                    {!! $joblisting->job->description !!}
                  </li>
                </ul>
              <ul class="list-group list-group-bordered list-group-full">
               
              <li class="list-group-item ">
                <strong>Minimum Educational Qualification:</strong> {{$joblisting->job->qualification?$joblisting->job->qualification->name:''}}
              </li>
               <li class="list-group-item ">
                   <strong>Expires:</strong>  {{date("F j, Y",strtotime($joblisting->expires))}}({{\Carbon\Carbon::parse($joblisting->expires)->diffForHumans()}})
                  </li>
            </ul>
              
          </div>
          @empty
          <div class="alert dark alert-primary" role="alert">
                  <h4>No Jobs</h4>
                  <p>
                   No Jobs has been created
                  </p>
                </div>
          @endforelse
           {!! $joblistings->appends(Request::capture()->except('page'))->render() !!}
          <div class="site-action" data-plugin="actionBtn">
          <button type="button" class=" btn-raised btn btn-success btn-floating" data-toggle="modal" data-target="#addJoblistingModal">
            <i class="icon md-plus animation-scale-up" aria-hidden="true"></i>
            
          </button>
              </div>
    		</div>
        <div class="col-md-4">
                 <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Filters</h3>
                
              </div>
              <form class="" action="{{url('recruits')}}" method="get" >


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
  <!-- End Page -->
  @include('recruit.modals.addJoblisting')
   @include('recruit.modals.editJoblisting')
  
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
        $('.summernote').summernote();
        $('.select2').select2();
        $('.ej_cont').hide();
          $('.ej_elt').attr('required',false);
         $('.input-daterange').datepicker({
    autoclose: true
});
    $('.datepicker').datepicker({
    autoclose: true,
    format:'yyyy-mm-dd'
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

 $('#target').on('change', function() {
      type= $(this).val();

      if(type==1){
        $('.ij_cont').show();
         $('.ij_elt').attr('required',true);
         $('.ej_cont').hide();
          $('.ej_elt').attr('required',false);
      }
      if(type==2){
        $('.ij_cont').hide();
         $('.ij_elt').attr('required',false);
          $('.ej_cont').show();
          $('.ej_elt').attr('required',true);
      }
      
     
    });





   

    $(document).on('submit','#addJobListingForm',function(event){
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
               $('#addJoblistingModal').modal('toggle');
         

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

    var country=$('#country').val();
var state=$('#state').val();
      $('#country').select2({
        ajax: {
         delay: 250,
         processResults: function (data) {
              return {        
        results: data
          };
        },
        url: function (params) {
        return '{{url('location/country')}}';
        } 
        }
    });
       $('#state').select2({
        ajax: {
         delay: 250,
         processResults: function (data) {
              return {        
        results: data
          };
        },
        url: function (country) {
        return '{{url('location/state')}}/'+$('#country').val();
        } 
        }
    });
  

    });

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