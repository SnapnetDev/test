	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Payroll Settings')}}</li>
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
		              <h3 class="panel-title">TMSA Policy Settings</h3>
		              <div class="panel-actions">
                			

              			</div>
		            	</div>
		            	<form id="editTmsaPolicyForm" enctype="multipart/form-data">
		            <div class="panel-body">
		            <div class="col-md-6">
		            	@csrf
		            		
	          				
	          				<div class="form-group" >
	          					<h4>Out of Station Allowance (&#8358;)</h4>
	          					<input type="text" name="out_of_station" class="form-control" value="{{$tp->out_of_station}}">
	          				</div>
	          				<div class="form-group" >
	          					<h4>BRT Percentage (%)</h4>
	          					<input type="text" name="brt_percentage" class="form-control" value="{{$tp->brt_percentage}}">
	          				</div>
	          				<div class="form-group" >
	          					<h4>Approval Workflow</h4>
	          					<select class="form-control" name="workflow_id">
	          						@forelse($workflows as $workflow)
	          						<option value="{{$workflow->id}}" {{$tp->workflow_id==$workflow->id?'selected':''}}>{{$workflow->name}}</option>
	          						@empty
	          						<option value="0">Please Create a Workflow</option>
	          						@endforelse
	          						
	          					</select>
	          				</div>
	          				<input type="hidden" name=" type" value="tmsa_policy">
	          					            	
		            </div>
	                 
	          		</div>
	          		<div class="panel-footer">
	          			<div class="form-group">
	          					<button class="btn btn-info" >Save Changes</button>
	          				</div>
	          		</div>
	          		</form>
		          </div>

	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">
	    		
	    	</div>
		</div>
	  </div>
	  
	    <script type="text/javascript">
  	
  	

  	$(function() {
  	$(document).on('submit','#editTmsaPolicyForm',function(event){
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
		           
					$( "#ldr" ).load('{{url('payrollsettings/tmsa_policy')}}');
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


 
  	

  </script>

