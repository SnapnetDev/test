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
		              <h3 class="panel-title">{{$salary_category->name}} Project Salary Category Timesheets for {{date('F,Y',strtotime($date))}}</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#uploadProjectSalaryCategoryTimesheetModal">Upload Timesheet</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            <div class="table-responsive">
	                  <table  class="table table-striped " id="dataTable">
		                    <thead>
		                      <tr>
		                        <th>S/N:</th>
		                        <th>Staff ID:</th>
		                        <th>User:</th>
		                        @foreach($components as $component)
		                        <th>
		                        {{$component->name}}
		                        </th>
		                        @endforeach
		                       
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@php
		                    		$sn=1;
		                    	@endphp
		                    	@foreach($users as $user)
		                    	<tr>
		                    		<td>{{$sn}}</td>
		                    		<td>{{$user->emp_num}}</td>
		                    		<td>{{$user->name}}</td>
		                    		@foreach($components as $component)
		                    		<td>
		                    			@if($component->project_salary_timesheets)
		                    			@foreach($component->project_salary_timesheets()->where('for',$date)->get() as $timesheet)
		                    				{{$user->id==$timesheet->user_id && $component->id==$timesheet->pace_salary_component_id?$timesheet->days:''}}
		                    			@endforeach
		                    			@endif
	                    			</td>
	                    			@php
	                    				$sn++;                    			
	                    			@endphp
		                    		@endforeach
		                    			                    		
		                    		
		                    	</tr>
		                    	
		                    	@endforeach
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
	   @include('payrollsettings.modals.uploadprojectsalarycategorytimesheets')
	  {{-- edit grade modal --}}
	   
	  <!-- End Page -->
	    <script type="text/javascript">
	    	 $(document).ready( function () 
	    	 {
    $('#dataTable').DataTable();
    $('.sc-status').bootstrapToggle({
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
} );
  	$(function() {
  
  		$('#uploadProjectSalaryCategoryTimesheetForm').submit(function(event){
  	
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
		           $('#uploadProjectSalaryCategoryTimesheetModal').modal('toggle');
				// 	 $( "#ldr" ).load('{{url('payrollsettings/project_salary_category_timesheets?project_category_id=')}}'+{{$salary_category->id}}+'&month='+{{date('m-Y',strtotime($date))}});
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
  });
  	

  	
  	

  
 

  </script>

