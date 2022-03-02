	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Employee Settings')}}</li>
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
		              <h3 class="panel-title">Project Salary Categories</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addPaceSalaryCategoryModal">Add Project Salary Category</button>
                			<button class="btn btn-info" data-toggle="modal" data-target="#uploadPaceSalaryCategoryTimesheetModal">Upload Project Salary Timesheet</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            <div class="table-responsive">
	                  <table  class="table table-striped " id="dataTable">
		                    <thead>
		                      <tr>
		                        <th>Name:</th>
		                        <th>Unionized:</th>
		                        <th>Uses Timesheet:</th>
		                         <th>Calculates Tax</th>
		                        <th>Basic Salary:</th>
		                        <th>Relief:</th>
		                        <th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($salary_categories as $sal_category)
		                    	<tr>
		                    		<td>{{$sal_category->name}}</td>
		                    		<td>{{ $sal_category->unionized == 1 ? 'Unionized' : 'Non-Unionized' }}</td>
		                    		<td>{{ $sal_category->uses_timesheet == 1 ? 'Uses Timesheet' : 'No Timesheet' }}</td>
		                    		<td>{{ $sal_category->uses_tax == 1 ? 'Uses Tax' : 'No Tax' }}</td>
		                    		<td>{{$sal_category->basic_salary}}</td>
		                    		<td>{{$sal_category->relief}}</td>				                    		
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    	<div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
			                    	        <a class="dropdown-item setting-linker" href="{{url('payrollsettings/project_salary_components?project_salary_category_id='.$sal_category->id)}}"><i class="fa fa-level-down" aria-hidden="true"></i>&nbsp;Category Components</a>
				                    		@if($sal_category->uses_timesheet==1)
				                    		<a class="dropdown-item" id="{{$sal_category->id}}" onclick="viewTimesheets(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Monthly Timesheet</a>
				                    		<a class="dropdown-item" href="{{url('payrollsettings/download_timesheet_upload_template?salary_category_id='.$sal_category->id)}}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Timesheet Template </a>
				                    		@endif
				                      	<a class="dropdown-item" id="{{$sal_category->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Project Salary Category</a>
				                       <a class="dropdown-item" id="{{$sal_category->id}}" onclick="deleteSalaryCategory(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Project Salary Category</a>
				                      
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
	    	<div class="col-md-12 col-xs-12">
	    		
	    	</div>
		</div>
	  </div>
	  {{-- Add Grade Modal --}}
	   @include('payrollsettings.modals.addpacecategory')
	  {{-- edit grade modal --}}
	   @include('payrollsettings.modals.editpacecategory')
	   {{-- edit grade modal --}}
	   @include('payrollsettings.modals.uploadallpacecategorytimesheet')

	   @include('payrollsettings.modals.viewprojectsalarycategorytimesheets')
	  <!-- End Page -->
	    <script type="text/javascript">
	    	 $(document).ready( function () 
	    	 {
			    $('#dataTable').DataTable();
			    $('.sc-display').bootstrapToggle({
			      on: 'on',
			      off: 'off',
			      onstyle:'info',
			      offstyle:'default'
			    });
			    $('.sc-taxable').bootstrapToggle({
			      on: 'on',
			      off: 'off',
			      onstyle:'info',
			      offstyle:'default'
			    });
			     $('.monthpicker').datepicker({
    autoclose: true,
    format:'mm-yyyy',
     viewMode: "months", 
    minViewMode: "months"
});
			} );

  	$(function() 
  	{ 
  	  $('#uploadAllProjectSalaryCategoryTimesheetForm').submit(function(event){  
      
		 event.preventDefault();
		 toastr.info('Processing ...','Info',{timeOut: 0,closeButton: true,extendedTimeOut:0 });
		  
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
		        	toastr.clear();
		            toastr.success("Changes saved successfully",'Success');
		           $('#uploadPaceSalaryCategoryTimesheetModal').modal('toggle');
				
				location.reload();

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
  	  $('#addPaceSalaryCategoryForm').submit(function(event){
  	
		 
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
		           $('#addPaceSalaryCategoryModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/project_salary_categories')}}');
				location.reload();

		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr.error(valchild[0]);
							});  
							});
		        }
		    });
		    return event.preventDefault();
      
	});
  });

  	$(function() 
  	{
  		$('#editPaceSalaryCategoryForm').submit(function(event){
  	
// 		 event.preventDefault();
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
		            $('#editPaceSalaryCategoryModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/project_salary_categories')}}');
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
       return event.preventDefault();
		});

  $(document).on('click','#viewCategoryTimesheets',function(event){
   
    project_category_id=$("#timesheet_salary_category_id").val();
    month=$("#month").val();
    $('#viewTimesheetsModal').modal('toggle');
    tref="{{url('payrollsettings/project_salary_category_timesheets?project_category_id=')}}"+project_category_id+'&month='+month ;
    sessionStorage.setItem('phref',tref);
    location.reload();
    $( "#ldr" ).load('{{url('payrollsettings/project_salary_category_timesheets?project_category_id=')}}'+project_category_id+'&month='+month);
   

  });


  });

  	
  	function prepareEditData(project_category_id)
  	{
    $.get('{{ url('/payrollsettings/project_salary_category') }}/',{ project_category_id: project_category_id },function(data){
    	
     $('#editname').val(data.name);
     $('#editunionized').val(data.unionized);
     $('#edittimesheet').val(data.uses_timesheet);
     $('#editbasic_salary').val(data.basic_salary);
	$('#editrelief').val(data.relief);
     $('#editpcid').val(data.id);
      $('#edituses_tax').val(data.uses_tax);
      $('#edituses_daily_net').val(data.uses_daily_net);
		$('#edittax_rate').val(data.tax_rate);
        $('#edit_uses_direct_tax').val(data.uses_direct_tax);
     
    });
    $('#editPaceSalaryCategoryModal').modal();
  }

  function deleteSalaryCategory(project_category_id)
  {
    $.get('{{ url('/payrollsettings/delete_project_salary_category') }}/',{ project_category_id: project_category_id },function(data){
    	if (data=='success') {
 		toastr.success("Project Salary Category deleted successfully",'Success');
 		$( "#ldr" ).load('{{url('payrollsettings/project_salary_categories')}}');
    	}else{
    		toastr.error("Error deleting Project Salary category",'Success');
    	}
     
    });
  }
  function viewTimesheets(project_category_id) {
  	$("#timesheet_salary_category_id").val(project_category_id);
  	$('#viewTimesheetsModal').modal();
  }

  
 

  </script>

