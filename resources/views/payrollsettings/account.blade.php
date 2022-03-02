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
		              <h3 class="panel-title">Payroll Account Settings</h3>
		              <div class="panel-actions">
                			

              			</div>
		            	</div>
		            	<form id="editAccountForm" >
		            <div class="panel-body">
		            <div class="col-md-6">
		            		@csrf
	          				<div class="form-group">
	          					<h4>Account Number</h4>
	          					<input type="text" name="accountNum" class="form-control" value="{{$cad->accountNum}}">
	          				</div>
	          				<div class="form-group" >
	          					<h4>First Name</h4>
	          					<input type="text" name="first_name" class="form-control" value="{{$cad->first_name}}">
	          				</div>
	          				<div class="form-group">
	          					<h4>Last Name</h4>
	          					<input type="text" name="last_name" class="form-control" value="{{$cad->last_name}}">
	          				</div>
	          				<div class="form-group">
	          					<h4>Bank</h4>
	          					<select class="form-control" name="bank_id">
	          						@forelse($banks as $bank)
	          						<option value="{{$bank->id}}" }} {{$cad->bank_id==$bank->id ? 'selected':''}}>{{$bank->bank_name}}</option>
	          						@empty
	          						<option value="0">Please Add Banks</option>
	          						@endforelse
	          						
	          					</select>
	          				</div>
	          				<input type="hidden" name="type" value="account">
	          				
	          					            	
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
	  
	  <!-- End Page -->
	    <script type="text/javascript">

  	$(function() {
 
  	
  	$(document).on('submit','#editAccountForm',function(event){
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
		        	console.log(data);
		            // toastr.success("Changes saved successfully",'Success');
		           
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

