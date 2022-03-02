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
		              <h3 class="panel-title">Payroll Policy Settings</h3>
		              <div class="panel-actions">


              			</div>
		            	</div>
		            	<form id="editPayrollPolicyForm" enctype="multipart/form-data">
		            <div class="panel-body">
		            <div class="col-md-6">
		            	@csrf
						<div class="form-group" >
							<h4>What Tax do you use?</h4>
							<input type="radio" id="old_tax" {{$pp->tax_preference=='old'?'checked':''}} name="tax_preference" value="old"> Old
							<input type="radio" id="new_tax" {{$pp->tax_preference=='new'?'checked':''}} name="tax_preference" value="new"> New
						</div>
		            		<div class="form-group" >
	          					<h4>Does Working days includes Weekends and Holidays?</h4>
	          					<input type="radio" id="old_tax" {{$pp->payroll_runs==1?'checked':''}} name="when" value="1"> Yes
	          					<input type="radio" id="next_month" {{$pp->payroll_runs==0?'checked':''}} name="when" value="0"> No
	          				</div>
	          				<div class="form-group" >
	          					<h4>How is your Gross pay Displayed?</h4>
	          					<input type="radio" id="static" {{$pp->show_all_gross==1?'checked':''}} name="show_all_gross" value="1"> Static
	          					<input type="radio" id="dynamic" {{$pp->show_all_gross==0?'checked':''}} name="show_all_gross" value="0"> Dynamic
	          				</div>
	          				<div class="form-group" >
	          					<h4>Basic Pay Percentage</h4>
	          					<input type="text" name="basic_pay_percentage" class="form-control" value="{{$pp->basic_pay_percentage}}">
	          				</div>
                        <div class="form-group" >
                            <h4>Does Your payroll process require an approval?</h4>
                            <input type="radio" id="uses_approval_yes" {{$pp->uses_approval==1?'checked':''}} name="uses_approval" value="1"> Yes
                            <input type="radio" id="uses_approval_no" {{$pp->uses_approval==0?'checked':''}} name="uses_approval" value="0"> No
                        </div>
	          				<div class="form-group" >
	          					<h4>Approval Workflow</h4>
	          					<select class="form-control" name="workflow_id">
	          						@forelse($workflows as $workflow)
	          						<option value="{{$workflow->id}}" {{$pp->workflow_id==$workflow->id?'selected':''}}>{{$workflow->name}}</option>
	          						@empty
	          						<option value="0">Please Create a Workflow</option>
	          						@endforelse

	          					</select>
	          				</div>
	          				<div class="form-group" >
	          					<h4>What type of Payroll do you run?</h4>
	          					<input type="checkbox" class="active-toggle payroll_status" id="use_office" {{$pp->use_office==1?'checked':''}} > Office Payroll
	          					<input type="checkbox" class="active-toggle payroll_status" id="use_tmsa" {{$pp->use_tmsa==1?'checked':''}} > TMSA Payroll
	          					<input type="checkbox" class="active-toggle payroll_status" id="use_project" {{$pp->use_project==1?'checked':''}} > Project Payroll
	          				</div>
	          				<input type="hidden" name=" type" value="payroll_policy">

		            </div>

	          		</div>
	          		<div class="panel-footer">
	          			<div class="form-group">
	          					<button class="btn btn-info" >Save Changes</button>
	          				</div>
	          		</div>
	          		</form>
		          </div>
		        

		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Specific Salary Component Types</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addSalComponentTypeModal">Add Component Type</button>

              			</div>
		            	</div>
		            <div class="panel-body">

		            	<div class="table-responsive">
		            <table class="table">
		            	<thead>
		            		<tr>
		            			<th>Component Category Name</th>
		            			<th>Display</th>
		            			<th>Action</th>
		            		</tr>
		            	</thead>
		            	<tbody>
		            		@forelse ($sal_comp_types as $sal_comp_type)
		            		<tr>
		            			<td>{{$sal_comp_type->name}}</td>
		            			<td><input type="checkbox" class="active-toggle sc-display" id="{{$sal_comp_type->id}}" {{$sal_comp_type->display == 1?'checked':''}} data-size="mini"></td>
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$sal_comp_type->id}}" onclick="prepareSEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Specific Salary Component Types</a>
				                       <a class="dropdown-item" id="{{$sal_comp_type->id}}" onclick="deleteSssct(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Specific Salary Component Types</a>
				                       <a class="dropdown-item" id="{{$sal_comp_type->id}}" href="{{url('payrollsettings/downloadssctypetemplate?ssct_id=').$sal_comp_type->id}}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Specific Salary Component Upload Template</a>

				                    </div>
				                  </div>
				              </td>
		            		</tr>
		            		@empty
		            		<tr>
		            			<td colspan="6">No Salary Component Types</td>
		            		</tr>

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
	    @include('payrollsettings.modals.addlatenesspolicy')
	   @include('payrollsettings.modals.addSalComponentType')
	  {{-- edit grade modal --}}
	   @include('payrollsettings.modals.editlatenesspolicy')
	   @include('payrollsettings.modals.editSalComponentType')
	  <!-- End Page -->
	    <script type="text/javascript">
  	$(function() {


    $('#lps').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });
     $('.sc-status').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });
      $('.sc-display').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });

     $('.payroll_status').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });


  	});

  	$(function() {

  		$('#addLatenessPolicyForm').submit(function(event){

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
		           $('#addLatenessPolicyModal').modal('toggle');
					$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
					// console.log(data);

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
  		$('#addSalComponentTypeForm').submit(function(event){

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
		           $('#addSalComponentTypeModal').modal('toggle');
					$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
					// console.log(data);

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
  		$('#editSalComponentTypeForm').submit(function(event){

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
		           $('#editSalComponentTypeModal').modal('toggle');
					$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
					// console.log(data);

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
  		$('#editLatenessPolicyForm').submit(function(event){

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
		            $('#editLatenessPolicyModal').modal('toggle');
					$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
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
  		$('#editPayrollPolicyForm').submit(function(event){

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

				// 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
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
  	 $('.sc-status').on('change', function() {
  		lateness_policy_id= $(this).attr('id');

  		 $.get('{{ url('/payrollsettings/change_lateness_policy_status') }}/',{ lateness_policy_id: lateness_policy_id },function(data){
  		 	if (data==1) {
  		 		toastr.success("Lateness Policy Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Lateness Policy Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
  		 });
  	});
  	 $('.sc-display').on('change', function() {
  		specific_salary_component_type_id= $(this).attr('id');

  		 $.get('{{ url('/payrollsettings/change_specific_salary_component_type_display') }}/',{ specific_salary_component_type_id: specific_salary_component_type_id },function(data){
  		 	if (data==1) {
  		 		toastr.success("Specific Salary Component Type Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Specific Salary Component Type Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
  		 });
  	});





  	   $('#lps').on('change', function() {

		 $.get('{{ url('/payrollsettings/switch_lateness_policy') }}/',function(data){
  		 	if (data==1) {
  		 		toastr.success("Lateness Policy Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Lateness Policy Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
  		 });
		});
  	   $('#use_office').on('change', function() {

		 $.get('{{ url('/payrollsettings/switch_office_payroll_policy') }}/',function(data){
  		 	if (data==1) {
  		 		toastr.success("Office Payroll Usage Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Office Payroll Usage Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
  		 });
		});
  	   $('#use_tmsa').on('change', function() {

		 $.get('{{ url('/payrollsettings/switch_tmsa_payroll_policy') }}/',function(data){
  		 	if (data==1) {
  		 		toastr.success("TMSA Payroll Usage Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("TMSA Payroll Usage Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
  		 });
		});
  	   $('#use_project').on('change', function() {

		 $.get('{{ url('/payrollsettings/switch_project_payroll_policy') }}/',function(data){
  		 	if (data==1) {
  		 		toastr.success("Project Payroll Usage Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Project Payroll Usage Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
  		 });
		});
  });
  // 	$(function() {
  // 	$(document).on('click','.sc-status',function(event){
  // 		lateness_policy_id= $(this).attr('id');

  // 		 $.get('{{ url('/payrollsettings/change_lateness_policy_status') }}/',{ lateness_policy_id: lateness_policy_id },function(data){
  // 		 	if (data==1) {
  // 		 		toastr.success("Lateness Policy Enabled",'Success');
  // 		 	}
  // 		 	if(data==2){
  // 		 		toastr.warning("Lateness Policy Disabled",'Success');
  // 		 	}
  // 		 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
  // 		 });
  // 	});
  // });

  	function prepareEditData(lateness_policy_id){
    $.get('{{ url('/payrollsettings/lateness_policy') }}/',{ lateness_policy_id: lateness_policy_id },function(data){

     $('#edit_policy_name').val(data.policy_name);
     $('#edit_late_minute').val(data.late_minute);
     $('#edit_deduction').val(data.deduction);
     $('#edit_ssctype_id').val(data.specific_salary_component_type_id);
     $('#edit_payroll').val(data.payroll);


    if (data.deduction_type==1) {
    	$("#edit_percentage").prop("checked", true);
    	$("#edit_amount").prop("checked", false);
    }else{
    	$("#edit_amount").prop("checked", true);
    	$("#edit_deduction").prop("checked", false);
    }


     $('#editid').val(data.id);
    });
    $('#editLatenessPolicyModal').modal();
  }

  function deleteLatenessPolicy(lateness_policy_id){
    $.get('{{ url('/payrollsettings/delete_lateness_policy') }}/',{ lateness_policy_id: lateness_policy_id },function(data){
    	if (data=='success') {
 		toastr.success("Lateness Policy deleted successfully",'Success');
 		$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
    	}else{
    		toastr.error("Error deleting Lateness Policy",'Success');
    	}

    });
  }

  function prepareSEditData(ssct_id){
    $.get('{{ url('/payrollsettings/specific_salary_component_type') }}/',{ ssct_id: ssct_id },function(data){

     $('#esalname').val(data.name);

     $('#esalid').val(data.id);
    });
    $('#editSalComponentTypeModal').modal();
  }

  function deleteSssct(ssct_id){
    $.get('{{ url('/payrollsettings/delete_specific_salary_component_type') }}/',{ ssct_id: ssct_id },function(data){
    	if (data=='success') {
 		toastr.success("Specific Salary Component Type deleted successfully",'Success');
 		$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
    	}else{
    		toastr.error("Error deleting Specific Salary Component Type",'Success');
    	}

    });
  }
  </script>

