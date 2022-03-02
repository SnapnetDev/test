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
		              <h3 class="panel-title"> Project Salary Components</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addPaceSalaryComponentModal">Add Project Salary Component</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            	<button class="btn btn-danger" id='multi-delete'>Delete Selected Components</button>
		            	<button class="btn btn-success" id='multi-active'>Enable\Disable Selected Components</button>
		            	<button class="btn btn-success" id='multi-taxable'>Enable\Disable Taxable For Selected Components</button>
		            <div class="table-responsive">
	                  <table  class="table table-striped " id="dataTable">
		                    <thead>
		                      <tr>
		                          
		                        <th>Name:</th>
		                        <th> Project Salary Category</th>
		                        <th>Type:</th>
		                        <th>Fixed:</th>
		                        <th>Constant:</th>
		                         <th>Formula:</th>
		                        <th>Amount</th>
		                        <th>Comment</th>
		                        <th>Uses Days</th>
								<th>Uses<br> Anniversary</th>
								<th>For<br> Probationers</th>
		                        <th>Taxable</th>
		                        <th>Status</th>
		                         <th>Exemption List</th>
		                          <th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($pscs as $pace_component)
		                    	<tr id="{{$pace_component->id}}">
		                    	    
		                    		<td>{{$pace_component->name}}</td>
		                    		<td>{{$pace_component->pace_salary_category?$pace_component->pace_salary_category->name:''}}</td>
		                    		<td>{{ $pace_component->type == 1 ? 'Allowance' : 'Deduction' }}</td>
		                    		<td>{{ $pace_component->fixed == 1 ? 'Yes' : 'No' }}</td>
		                    		<td>{{$pace_component->constant}}</td>
		                    		<td>{{$pace_component->formula}}</td>
		                    		<td>{{$pace_component->amount}}</td>
		                    		<td>{{$pace_component->comment}}</td>
		                    		<td>{{ $pace_component->uses_days == 1 ? 'Yes' : 'No' }}</td>
									<td>{{ $pace_component->uses_anniversary == 1 ? 'Yes' : 'No' }}</td>
									<td>{{ $pace_component->uses_probation == 1 ? 'Yes' : 'No' }}</td>
		                    		<td><input type="checkbox" class="active-toggle sc-taxable" id="{{$pace_component->id}}" {{$pace_component->taxable == 1?'checked':''}} data-size="mini">
		                    		<td><input type="checkbox" class="active-toggle sc-status" id="{{$pace_component->id}}" {{$pace_component->status == 1?'checked':''}} data-size="mini">
		            				
		                    		<td>
		                    			
		                    			@foreach($pace_component->exemptions as $user)
		                    			@if ($loop->last)
									        {{$user->name}}
								        @else
								        	{{$user->name}},
									    @endif
		                    			
		                    			
		                    			 
		                    			@endforeach
		                    		</td>
		                    		
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$pace_component->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Project Salary Component</a>
				                       <a class="dropdown-item" id="{{$pace_component->id}}" onclick="deleteSalaryComponent(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Project Salary Component</a>
				                      
				                    </div>
				                  </div></td>
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
	   @include('payrollsettings.modals.addpacecomponent')
	  {{-- edit grade modal --}}
	   @include('payrollsettings.modals.editpacecomponent')
	  <!-- End Page -->
	    <script type="text/javascript">
	    	 $(document).ready( function () {
    
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
  $('#fixed').on('click', function() {       
         $('#formula').attr('required',false);
          $('#amount').attr('required',true);
    });

  $('#derivative').on('click', function() {       
         $('#amount').attr('required',false);
          $('#formula').attr('required',true);
    });
  $('#editpcfixed').on('click', function() {       
         $('#formula').attr('required',false);
          $('#amount').attr('required',true);
    });

  $('#editpcderivative').on('click', function() {       
         $('#amount').attr('required',false);
          $('#formula').attr('required',true);
    });
  $('#addPaceSalaryComponentForm').submit(function(event){
  	
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
		           $('#addPaceSalaryComponentModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/project_salary_components')}}');
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
  		$('#editPaceSalaryComponentForm').submit(function(event){
  	
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
		            $('#editPaceSalaryComponentModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/project_salary_components')}}');
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
  			$('#uploadProjectsSalaryComponentForm').submit(function(event){
  	
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
		            $('#addPaceSalaryComponentModal').modal('toggle');
		          //  console.log(data);
				// 	$( "#ldr" ).load('{{url('payrollsettings/project_salary_components')}}');
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

  	
  	function prepareEditData(project_salary_component_id){
    $.get('{{ url('/payrollsettings/project_salary_component') }}/',{ project_salary_component_id: project_salary_component_id },function(data){
    	
     $('#editpcname').val(data.name);
     $('#editpcsalarycategory').val(data.pace_salary_category_id);
     $('#editpc').val(data.type);
     $('#editpcuses_days').val(data.uses_days);
     $('#editpcuses_anniversary').val(data.uses_anniversary);
	 $('#editpcuses_probation').val(data.uses_probation);
     $('#editpcamount').val(data.fixed);
     $('#editpcconstant').val(data.constant);
     $('#editpcformula').val(data.formula);
     $('#editpcamount').val(data.amount);
      $('#editpcgl_code').val(data.gl_code);
       $('#editpcproject_code').val(data.project_code);
       $('#editpctaxable').val(data.taxable);
      $("#edittcexemptions").find('option')
    .remove();
    console.log(data.type);
    if (data.type==1) {
    	$("#editpcallowance").prop("checked", true);
    	$("#editpcdeduction").prop("checked", false);
    }else{
    	$("#editpcdeduction").prop("checked", true);
    	$("#editpcallowance").prop("checked", false);
    }
    if (data.fixed==1) {
    	$("#editpcfixed").prop("checked", true);
    	$("#editpcderivative").prop("checked", false);
    }else{
    	$("#editpcderivative").prop("checked", true);
    	$("#editpcfixed").prop("checked", false);
    }
    
     jQuery.each( data.exemptions, function( i, val ) {
       $("#editpcexemptions").append($('<option>', {value:val.id, text:val.name,selected:'selected'}));
       // console.log(val.name);
              });	
     $('#editpcid').val(data.id);
    });
    $('#editPaceSalaryComponentModal').modal();
  }

  function deleteSalaryComponent(project_salary_component_id){
    $.get('{{ url('/payrollsettings/delete_project_salary_component') }}/',{ project_salary_component_id: project_salary_component_id },function(data){
    	if (data=='success') {
 		toastr.success("Project Salary Component deleted successfully",'Success');
 		$( "#ldr" ).load('{{url('payrollsettings/project_salary_components')}}');
    	}else{
    		toastr.error("Error deleting Project Salary Component",'Success');
    	}
     
    });
  }
$("#dataTable tbody tr").click( function( e ) {
    	console.log($(this).attr('id'));
        if ( $(this).hasClass('row_selected') ) {
            $(this).removeClass('row_selected');
        }
        else {
            oTable.$('tr.row_selected')//.removeClass('row_selected');
            $(this).addClass('row_selected');
        }
    });

     
    /* Add a click handler for the delete row */
    $('#multi-delete').click( function() {
    	 alertify.confirm('Are you sure you want to delete these components?', function () {
    	 	oTable.$('tr.row_selected').each(function() {
    	 		var project_salary_component_id= $( this ).attr('id');
			   $.get('{{ url('/payrollsettings/delete_project_salary_component') }}/',{ project_salary_component_id: project_salary_component_id },function(data){ 
				    });
			});
			var anSelected = fnGetSelected( oTable );
       		 $(anSelected).remove();
              alertify.success('Project Salary Component deleted succesfully');
            }, function () {
              alertify.error('Project Salary Components not deleted');
            });

        
    } );
    $('#multi-active').click( function() {
    	 alertify.confirm('Are you sure you want to activate/Deactivate these components?', function () {
    	 	oTable.$('tr.row_selected').each(function() {
    	 		var project_salary_component_id= $( this ).attr('id');
			   $.get('{{ url('/payrollsettings/change_project_salary_component_status') }}/',{ project_salary_component_id: project_salary_component_id },function(data){ 
				    });
			});
			
              alertify.success('Project Salary Components status changed succesfully');
            }, function () {
              
            });

        
    } );

    $('#multi-taxable').click( function() {
    	 alertify.confirm('Are you sure you want to alter the taxable status of these components?', function () {
    	 	oTable.$('tr.row_selected').each(function() {
    	 		var project_salary_component_id= $( this ).attr('id');
			    $.get('{{ url('/payrollsettings/change_project_salary_component_taxable') }}/',{ project_salary_component_id: project_salary_component_id },function(data){ 
				    });
			});
			
              alertify.success('Project Salary Components Taxable status changed succesfully');
            }, function () {
             
            });

        
    } );
     
     

    oTable = $('#dataTable').dataTable( );

 
 
/* Get the rows which are currently selected */
function fnGetSelected( oTableLocal )
{
    return oTableLocal.$('tr.row_selected');
}

  
 
 $(function(){

  $('#exemptions').select2({
	   ajax: {
		 delay: 250,
		 processResults: function (data) {
					return {				
		results: data
			};
		},
		url: function (params) {
		return '{{url('users')}}/search';
		}	
		}
	});
  $('#editpcexemptions').select2({
	   ajax: {
		 delay: 250,
		 processResults: function (data) {
					return {				
		results: data
			};
		},
		url: function (params) {
		return '{{url('users')}}/search';
		}	
		}
	});
  });
	$(function() {
  	 $('.sc-status').on('change', function() {
  		project_salary_component_id= $(this).attr('id');
  		
  		 $.get('{{ url('/payrollsettings/change_project_salary_component_status') }}/',{ project_salary_component_id: project_salary_component_id },function(data){
  		 	if (data==1) {
  		 		toastr.success(" Project Salary Component Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning(" Project Salary Component Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/project_salary_components')}}');
  		 });
  	});
  	 	});

	$(function() {
  	 $('.sc-taxable').on('change', function() {
  		project_salary_component_id= $(this).attr('id');
  		
  		 $.get('{{ url('/payrollsettings/change_project_salary_component_taxable') }}/',{ project_salary_component_id: project_salary_component_id },function(data){
  		 	if (data==1) {
  		 		toastr.success(" Project Salary is now Taxable",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning(" Project Salary is no more Taxable",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/project_salary_components')}}');
  		 });
  	});
  	$('#dataTable').DataTable();
  	 	});
  </script>

