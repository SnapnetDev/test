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
		              <h3 class="panel-title"> TMSA Salary Components</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addTMSAComponentModal">Add TMSA Salary Component</button>

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
		                        
		                        <th>Type:</th>
		                        <th>Fixed:</th>
		                        <th>Constant:</th>
		                         <th>Formula:</th>
		                        <th>Amount</th>
		                         
		                        <th>Comment</th>
		                        <th>Taxable</th>
		                        <th>Status</th>
		                        <th>Year</th>
		                          <th>Rate</th>

		                         <th>Exemption List</th>
		                         <th>Month</th>
		                          <th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($tcs as $tmsa_component)
		                    	<tr id="{{$tmsa_component->id}}">
		                    		<td>{{$tmsa_component->name}}</td>
		                    		
		                    		<td>{{ $tmsa_component->type == 1 ? 'Allowance' : 'Deduction' }}</td>
		                    		<td>{{ $tmsa_component->fixed == 1 ? 'Yes' : 'No' }}</td>
		                    		<td>{{$tmsa_component->constant}}</td>
		                    		<td>{{$tmsa_component->formula}}</td>
		                    		<td>{{$tmsa_component->amount}}</td>
		                    		
		                    		<td>{{$tmsa_component->comment}}</td>
		                    		<td><input type="checkbox" class="active-toggle sc-taxable" id="{{$tmsa_component->id}}" {{$tmsa_component->taxable == 1?'checked':''}} data-size="mini">
		                    		<td><input type="checkbox" class="active-toggle sc-status" id="{{$tmsa_component->id}}" {{$tmsa_component->status == 1?'checked':''}} data-size="mini">
		            				</td>
		            				<td>{{$tmsa_component->year}}</td>
		                    		<td>{{$tmsa_component->rate}}</td>
		                    		<td>
		                    			
		                    			
		                    			@foreach($tmsa_component->exemptions as $user)
		                    			@if ($loop->last)
									        {{$user->name}}
								        @else
								        	{{$user->name}},
									    @endif
		                    			
		                    			
		                    			 
		                    			@endforeach
		                    		</td>
		                    		<td>
		                    			
		                    			
		                    			@foreach($tmsa_component->months as $month)
		                    			@if ($loop->last)
									        {{$month->name.'-'.$month->year}}
								        @else
								        	{{$month->name.'-'.$month->year}},
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
				                      <a class="dropdown-item" id="{{$tmsa_component->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit TMSA Salary Component</a>
				                       <a class="dropdown-item" id="{{$tmsa_component->id}}" onclick="deleteSalaryComponent(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete TMSA Salary Component</a>
				                      
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
	   @include('payrollsettings.modals.addtmsacomponent')
	  {{-- edit grade modal --}}
	   @include('payrollsettings.modals.edittmsacomponent')
	  <!-- End Page -->
	    <script type="text/javascript">
	    	 $(document).ready( function () {
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
  $('#fixed').on('click', function() {       
         $('#formula').attr('required',false);
          $('#amount').attr('required',true);
    });

  $('#derivative').on('click', function() {       
         $('#amount').attr('required',false);
          $('#formula').attr('required',true);
    });
  $('#edittcfixed').on('click', function() {       
         $('#formula').attr('required',false);
          $('#amount').attr('required',true);
    });

  $('#edittcderivative').on('click', function() {       
         $('#amount').attr('required',false);
          $('#formula').attr('required',true);
    });

  	$(document).on('submit','#addTMSAComponentForm',function(event){
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
		           $('#addTMSAComponentModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/tmsa_components')}}');
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
  	$(document).on('submit','#editTMSAComponentForm',function(event){
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
		            $('#editTMSAComponentModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/tmsa_components')}}');
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
  	$(document).on('submit','#uploadTMSAComponentForm',function(event){
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
		            $('#addTMSAComponentModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/tmsa_components')}}');
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

  	
  	function prepareEditData(tmsa_component_id){
    $.get('{{ url('/payrollsettings/tmsa_component') }}/',{ tmsa_component_id: tmsa_component_id },function(data){
    	
     $('#edittcname').val(data.name);
     // $('#edittccategory').val(data.pace_salary_category_id);
     $('#edittc').val(data.type);
     $('#edittcuses_month').val(data.uses_month);
     // $('#edittcmonths').val(data.fixed);
     $('#edittcconstant').val(data.constant);
     $('#edittcformula').val(data.formula);
     $('#edittcamount').val(data.amount);
      $('#edittcgl_code').val(data.gl_code);
       $('#edittcproject_code').val(data.project_code);
       $('#edittcyear').val(data.year);
       $('#edittcrate').val(data.rate);
       $('#edittctaxable').val(data.taxable);
      $("#edittcexemptions").find('option')
    .remove();
     $("#edittcmonths").find('option')
    .remove();
    console.log(data.type);
    if (data.type==1) {
    	$("#edittcallowance").prop("checked", true);
    	$("#edittcdeduction").prop("checked", false);
    }else{
    	$("#edittcdeduction").prop("checked", true);
    	$("#edittcallowance").prop("checked", false);
    }
    if (data.fixed==1) {
    	$("#edittcfixed").prop("checked", true);
    	$("#edittcderivative").prop("checked", false);
    }else{
    	$("#edittcderivative").prop("checked", true);
    	$("#edittcfixed").prop("checked", false);
    }
    
     jQuery.each( data.exemptions, function( i, val ) {
       $("#edittcexemptions").append($('<option>', {value:val.id, text:val.name,selected:'selected'}));
       // console.log(val.name);
              });	
     jQuery.each( data.months, function( i, val ) {
       $("#edittcmonths").append($('<option>', {value:val.id, text:val.name+'-'+val.year,selected:'selected'}));
       // console.log(val.name);
              });	
     $('#edittcid').val(data.id);
    });
    $('#editTMSAComponentModal').modal();
  }

  function deleteSalaryComponent(tmsa_component_id){
    $.get('{{ url('/payrollsettings/delete_tmsa_component') }}/',{ tmsa_component_id: tmsa_component_id },function(data){
    	if (data=='success') {
 		toastr.success("TMSA Component deleted successfully",'Success');
 		$( "#ldr" ).load('{{url('payrollsettings/tmsa_components')}}');
    	}else{
    		toastr.error("Error deleting TMSA Component",'Success');
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
    	 		var tmsa_component_id= $( this ).attr('id');
			   $.get('{{ url('/payrollsettings/delete_tmsa_component') }}/',{ tmsa_component_id: tmsa_component_id },function(data){ 
				    });
			});
			var anSelected = fnGetSelected( oTable );
       		 $(anSelected).remove();
              alertify.success('TMSA Component deleted succesfully');
            }, function () {
              alertify.error('TMSA Components not deleted');
            });

        
    } );
    $('#multi-active').click( function() {
    	 alertify.confirm('Are you sure you want to activate/Deactivate these components?', function () {
    	 	oTable.$('tr.row_selected').each(function() {
    	 		var tmsa_component_id= $( this ).attr('id');
			   $.get('{{ url('/payrollsettings/change_tmsa_component_status') }}/',{ tmsa_component_id: tmsa_component_id },function(data){ 
				    });
			});
			
              alertify.success('TMSA Components status changed succesfully');
            }, function () {
              
            });

        
    } );

    $('#multi-taxable').click( function() {
    	 alertify.confirm('Are you sure you want to alter the taxable status of these components?', function () {
    	 	oTable.$('tr.row_selected').each(function() {
    	 		var tmsa_component_id= $( this ).attr('id');
			    $.get('{{ url('/payrollsettings/change_tmsa_component_taxable') }}/',{ tmsa_component_id: tmsa_component_id },function(data){ 
				    });
			});
			
              alertify.success('TMSA Components Taxable status changed succesfully');
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
  $('#edittcexemptions').select2({
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
 $(function(){

  $('#months').select2({
	   ajax: {
		 delay: 250,
		 processResults: function (data) {
					return {				
		results: data
			};
		},
		url: function (params) {
		return '{{url('payrollsettings')}}/monthsearch';
		}	
		}
	});
  $('#edittcmonths').select2({
	   ajax: {
		 delay: 250,
		 processResults: function (data) {
					return {				
		results: data
			};
		},
		url: function (params) {
		return '{{url('payrollsettings')}}/monthsearch';
		}	
		}
	});
  });
	$(function() {
  	 $('.sc-status').on('change', function() {
  		tmsa_component_id= $(this).attr('id');
  		
  		 $.get('{{ url('/payrollsettings/change_tmsa_component_status') }}/',{ tmsa_component_id: tmsa_component_id },function(data){
  		 	if (data==1) {
  		 		toastr.success(" TMSA Component Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning(" TMSA Component Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/tmsa_components')}}');
  		 });
  	});
  	 	});

	$(function() {
  	 $('.sc-taxable').on('change', function() {
  		tmsa_component_id= $(this).attr('id');
  		
  		 $.get('{{ url('/payrollsettings/change_tmsa_component_taxable') }}/',{ tmsa_component_id: tmsa_component_id },function(data){
  		 	if (data==1) {
  		 		toastr.success(" TMSA is now Taxable",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning(" TMSA is no more Taxable",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/tmsa_components')}}');
  		 });
  	});
  	 	});
  </script>

