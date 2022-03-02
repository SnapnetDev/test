		<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item "><a class="linker" href="{{route('companies')}}">{{__('Companies')}}</a></li>
		    <li class="breadcrumb-item ">{{__($department->company->name)}}</li>
		    <li class="breadcrumb-item ">{{__('Departments')}}</li>
		     <li class="breadcrumb-item ">{{__($department->name)}}</li>
		     <li class="breadcrumb-item ">{{__('Jobs')}}</li>
		    <li class="breadcrumb-item active">{{__('You are Here')}}</li>
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
      <div class="page-content container-fluid">
      	<div class="row">
        	<div class="col-md-12 col-xs-12">
        		<div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Job Roles</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addJobModal">Add Job</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            	<br>
                	
	                  <table id="exampleTablePagination" data-toggle="table" 
	                  data-query-params="queryParams" data-mobile-responsive="true"
	                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
	                    <thead>
	                      <tr>
	                        <th >Name:</th>
	                        <th >Parent:</th>
	                        <th >Action</th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    	@forelse($jobs as $job)
	                    	<tr>
	                    		<td>{{$job->title}}</td>
	                    		<td>
	                    		@if($job->parent)
	                    		{{$job->parent->title}}
	                    		@else
	                    		None Selected
	                    		@endif
	                    		</td>
	                    		<td>
	                    			<div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                    data-toggle="dropdown" aria-expanded="false">
                      Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                      <a class="dropdown-item" id="{{$job->id}}" onclick="prepareEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Job</a>
                      <a class="dropdown-item" id="{{$job->id}}" href="#"><i class="fa fa-users" aria-hidden="true"></i>&nbsp;More</a>
                      <a class="dropdown-item" id="{{$job->id}}" onclick="deleteDepartment(this.id);"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Job</a>
                      
                    </div>
                  </div>
	                    		</td>
	                    	</tr>
	                    	@empty
	                    	@endforelse
	                    	
	                    </tbody>
	                  </table>
		          		</div>
		          </div>
	        	</div>
	    	</div>
		</div>

    
  </div>
  <!-- End Page -->
   {{-- add department modal --}}
   @include('settings.companysettings.modals.addjob')
	  {{-- edit department modal --}}
	  @include('settings.companysettings.modals.editjob')
	  

  <script type="text/javascript">
  	$(function() {
  

  	$(document).on('submit','#addJobForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('jobs.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           setTimeout(function(){
            window.location.reload();
           },2000);
           return; 
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
  	$(document).on('submit','#editJobForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('jobs.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           setTimeout(function(){
            window.location.reload();
           },2000);
           return;
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
  	function prepareEditData(job_id){
    $.get('{{ url('/job') }}/',{ salary_component_id: salary_component_id },function(data){
    	
     $('#editjobtitle').val(data.title);
     $('#editjobparent_id').val(data.parent_id);
     $('#editjobpersonnel').val(data.personnel);
      $("#editjobskills").find('option')
    .remove();
    console.log(data.type);
    if (data.type==1) {
    	$("#editscallowance").prop("checked", true);
    	$("#editscdeduction").prop("checked", false);
    }else{
    	$("#editscdeduction").prop("checked", true);
    	$("#editscallowance").prop("checked", false);
    }
    
     jQuery.each( data.skills, function( i, val ) {
       $("#editjobskills").append($('<option>', {value:val.id, text:val.name,selected:'selected'}));
       // console.log(val.name);
              });	
     $('#editjobid').val(data.id);
    });
    $('#editJobModal').modal();
  }
  function deleteJob(job_id){
  
  alertify.confirm('Are you sure you want to delete this job ?', function(){ 
  $.get('{{ url('settings/jobs/delete') }}/'+department_id,{
    department_id:department_id
  }, 
    function(data, status){
        if(data=="success"){
           toastr.success('Job Deleted Successfully');
           setTimeout(function(){
            window.location.reload();
           },2000);
           return; 
        }
        toastr.error(data);
    });
    });

}
  
  </script>
