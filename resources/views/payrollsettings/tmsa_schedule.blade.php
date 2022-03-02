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
        		
        		<div class="panel panel-info panel-line" style="height:auto">
		            <div class="panel-heading">
		              <h3 class="panel-title">TMSA Schedule</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addTmsaScheduleModal">Add TMSA Schedule</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            <div class="table-responsive">
	                  <table  class="table table-striped " id="dataTable">
		                    <thead>
		                      <tr>
		                        <th>Period:</th>
		                        <th>User:</th>
		                        <th>Day Rate Onshore:</th>
		                        <th>Day Rate Offshore:</th>
		                        <th>Paid Off-time Rate:</th>
		                        <th>Day Worked Offshore:</th>
		                        <th>Day Worked Onshore</th>
		                        <th>Paid Off Day</th>
		                        <th>BRT Days</th>
		                        <th>Living Allowance</th>
		                        <th>Trans Allowance</th>
		                        <th>Days Out of Station</th>
		                        <th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($tmsa_schedules as $tmsa_schedule)
		                    	<tr>
		                    		<td>{{date('F,Y',strtotime($tmsa_schedule->for))}}</td>
		                    		<td>@if($tmsa_schedule->user){{$tmsa_schedule->user->name}}@endif</td>
		                    		<td>{{$tmsa_schedule->day_rate_onshore}}</td>
		                    		<td>{{$tmsa_schedule->day_rate_offshore}}</td>
		                    		<td>{{$tmsa_schedule->paid_off_time_rate}}</td>
		                    		<td>{{$tmsa_schedule->days_worked_offshore}}</td>
		                    		<td>{{$tmsa_schedule->days_worked_onshore}}</td>
		                    		<td>{{$tmsa_schedule->paid_off_day}}</td>
		                    		<td>{{$tmsa_schedule->brt_days}}</td>	
		                    		<td>{{$tmsa_schedule->living_allowance}}</td>
		                    		<td>{{$tmsa_schedule->transport_allowance}}</td>
		                    		<td>{{$tmsa_schedule->days_out_of_station}}</td>		                    		
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    	<div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      	<a class="btn btn-sm" id="{{$tmsa_schedule->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil text-inverse m-r-10" aria-hidden="true"></i>&nbsp;Edit TMSA Schedule</a>
				                       <a class="btn btn-sm" id="{{$tmsa_schedule->id}}" onclick="deleteTmsaSchedule(this.id)"><i class="fa fa-trash text-inverse m-r-10" aria-hidden="true"></i>&nbsp;Delete TMSA Schedule</a>
				                      
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
	   @include('payrollsettings.modals.addTMSASchedule')
	  {{-- edit grade modal --}}
	   @include('payrollsettings.modals.editTMSASchedule')
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
     $('.monthpicker').datepicker({
    autoclose: true,
    format:'mm-yyyy',
     viewMode: "months", 
    minViewMode: "months"
});
} );
  	$(function() {
  

  	$(document).on('submit','#addTmsaScheduleForm',function(event){
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
		           $('#addTmsaScheduleModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/tmsa_schedules')}}');
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
  	$(function() {
  	$(document).on('submit','#editTmsaScheduleForm',function(event){
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
		            $('#editTmsaScheduleModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/tmsa_schedules')}}');
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
      
		});
  });
  	$(function() {
  	$(document).on('submit','#uploadTMSAScheduleForm',function(event){
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
		            $('#addTmsaScheduleModal').modal('toggle');
				// 	 $( "#ldr" ).load('{{url('payrollsettings/tmsa_schedules')}}');
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
      
		});
  });

  	

  	
  	function prepareEditData(tmsa_schedule_id)
  	{
    $.get('{{ url('/payrollsettings/tmsa_schedule') }}/',{ tmsa_schedule_id: tmsa_schedule_id },function(data){
    	
     $('#editusername').val(data.user.name);
     
     $('#editday_rate_onshore').val(data.day_rate_onshore);
     $('#editdays_worked_onshore').val(data.days_worked_onshore);
     $('#editday_rate_offshore').val(data.day_rate_offshore);
     $('#editdays_worked_offshore').val(data.days_worked_offshore);
     $('#editpaid_off_time_rate').val(data.paid_off_time_rate);
      $('#editpaid_off_day').val(data.paid_off_day);
       $('#editbrt_days').val(data.brt_days);
       $('#editliving_allowance').val(data.living_allowance);
       $('#edittransport_allowance').val(data.transport_allowance);
       $('#editdays_out_of_station').val(data.days_out_of_station);
   	
     $('#edittsid').val(data.id);
    });
    $('#editTmsaScheduleModal').modal();
  }

  function deleteTmsaSchedule(tmsa_schedule_id)
  {
    	$.get('{{ url('/payrollsettings/delete_tmsa_schedule') }}/',{ tmsa_schedule_id: tmsa_schedule_id },function(data){
    	if (data=='success') {
 		toastr.success(" TMSA Schedule deleted successfully",'Success');
 		$( "#ldr" ).load('{{url('payrollsettings/tmsa_schedules')}}');
    	}else{
    		toastr.error("Error deleting TMSA Schedule",'Success');
    	}
     
    });
  }

  
 

  </script>

