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
		              <h3 class="panel-title">Loan Policy Settings</h3>
		              <div class="panel-actions">
                			

              			</div>
		            	</div>

		            <div class="panel-body">


						@include('payrollsettings.loan_type.loan_create_modal')
						@include('payrollsettings.loan_type.loan_edit_modal')


						<div class="row">
							<div class="col-md-12" id="loan-type-management">

								@include('payrollsettings.loan_type.loan_type_list')

							</div>
							<div class="col-md-12">
								<form id="editLoanPolicyForm" enctype="multipart/form-data">
								@csrf

								<div class="form-group" >
									<h4>Annual Interest (%)</h4>
									<input type="text" name="annual_interest" class="form-control" value="{{$lp->annual_interest}}">
								</div>
								<div class="form-group" >
									<h4>Maximum Allowed (% of gross salary)</h4>
									<input type="text" name="maximum_allowed" class="form-control" value="{{$lp->maximum_allowed}}">
								</div>
								<div class="form-group" >
									<h4>Approval Workflow</h4>
									<select class="form-control" name="workflow_id">
										@forelse($workflows as $workflow)
											<option value="{{$workflow->id}}" {{$lp->workflow_id==$workflow->id?'selected':''}}>{{$workflow->name}}</option>
										@empty
											<option value="0">Please Create a Workflow</option>
										@endforelse

									</select>
								</div>
								<input type="hidden" name=" type" value="loan_policy">


								<div class="form-group">
									<button class="btn btn-info" >Save Changes</button>
								</div>

						</form>

							</div>
						</div>




	                 
	          		</div>
	          		<div class="panel-footer">
	          			<div class="form-group">

	          				</div>
	          		</div>

		          </div>

	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">
	    		
	    	</div>
		</div>
	  </div>
	  
	    <script type="text/javascript">
  	
  	

  	$(function() {
  		$('#editLoanPolicyForm').submit(function(event){
  	
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
		           
					$( "#ldr" ).load('{{url('payrollsettings/loan_policy')}}');
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

