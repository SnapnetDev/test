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
		              <h3 class="panel-title">Specific Salary Components</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addSpecificSalaryComponentModal">Add Specific Salary Component</button>

						  @if(isset(\App\Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value) && \App\Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value=='1' && \App\LatenessPolicy::where('company_id',companyId())->first()->status=='1')
						  <button class="btn btn-info" onclick="calcDeductions()">Calculate Deductions</button>
						  @endif

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
		                          <th>Created In:</th>
		                        <th>Name:</th>
		                        <th>Type:</th>
		                        <th>Employee Name:</th>
		                        <th>Category:</th>
		                        <th>Amount:</th>
		                        <th>Comment:</th>
		                         <th>GL Code</th>
		                          <th>Project Code</th>
		                        <th>Duration:</th>
		                        <th>Grants:</th>
		                        <th>Completed</th>
		                        <th>Taxable</th>
	                          	<th>Alter Status</th>
	                          	<th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($sscs as $salary_component)
		                    	<tr id="{{$salary_component->id}}">
		                    	    <td>{{ date("F, Y", strtotime($salary_component->created_at)) }}</td>
		                    		<td>{{$salary_component->name}}</td>
		                    		<td>{{$salary_component->type == 1 ? 'Allowance' : ($salary_component->type == 0? 'Deduction':($salary_component->type==2?'Tax Rebate':'')) }}</td>
		                    		<td>{{$salary_component->user ? $salary_component->user->name :''}}</td>
		                    		<td>{{$salary_component->ssc_type ? $salary_component->ssc_type->name:''}}</td>
		                    		<td>₦{{$salary_component->amount}}</td>
		                    		<td>{{$salary_component->comment}}</td>
		                    		<td>{{$salary_component->gl_code}}</td>
		                    		<td>{{$salary_component->project_code}}</td>
		                    		<td>{{$salary_component->duration}}</td>
		                    		<td>{{$salary_component->grants}}</td>
		                    		<td>{{$salary_component->completed==1?'Completed':'Not Completed'}}</td>
		                    		<td><input type="checkbox" class="active-toggle sc-taxable" id="{{$salary_component->id}}" {{$salary_component->taxable == 1?'checked':''}} data-size="mini"></td>
		                    		<td><input type="checkbox" class="active-toggle sc-status" id="{{$salary_component->id}}" {{$salary_component->status == 1?'checked':''}} {{$salary_component->completed==1?'disabled':''}} data-size="mini">

		                    		</td>


		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                    	@if($salary_component->grants==0)
				                    	<a class="dropdown-item" id="{{$salary_component->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Salary Component</a>
				                    	@endif
				                       <a class="dropdown-item" id="{{$salary_component->id}}" onclick="deleteSpecificSalaryComponent(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Salary Component</a>

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
	   @include('payrollsettings.modals.addspecificsalarycomponent')
	   @include('payrollsettings.modals.editspecificsalarycomponent')

	  <!-- End Page -->
	    <script type="text/javascript">
	    	 $(document).ready( function () {
	    	 	$('#dataTable thead tr').clone(true).appendTo( '#dataTable thead' );
    $('#dataTable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );

        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    var table = $('#dataTable').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        "pageLength": 50,
        "ordering": false
    } );
    // $('#dataTable').DataTable();
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

  		$('#addSpecificSalaryComponentForm').submit(function(event){

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
		           $('#addSalaryComponentModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/specific_salary_components')}}');
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
		$('#editSpecificSalaryComponentForm').submit(function(event){

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
		           $('#editSalaryComponentModal').modal('hide');
				// 	$( "#ldr" ).load('{{url('payrollsettings/specific_salary_components')}}');
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

		$('#uploadSpecificSalaryComponentForm').submit(function(event){

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
		            toastr.success("components uploaded successfully",'Success');
		           $('#addSalaryComponentModal').modal('toggle');
		           location.reload();
				// 	$( "#ldr" ).load('{{url('payrollsettings/specific_salary_components')}}');

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


  // 	$(function() {
  // 	$(document).on('click','.sc-status',function(event){
  // 		salary_component_id= $(this).attr('id');

  // 		 $.get('{{ url('/payrollsettings/change_specific_salary_component_status') }}/',{ specific_salary_component_id: salary_component_id },function(data){
  // 		 	if (data==1) {
  // 		 		toastr.success("Salary Component Activated",'Success');
  // 		 	}
  // 		 	if(data==2){
  // 		 		toastr.warning("Salary Component Paused",'Success');
  // 		 	}
  // 		 	$( "#ldr" ).load('{{url('payrollsettings/specific_salary_components')}}');
  // 		 });
  // 	});
  // });


  	function prepareEditData(specific_salary_component_id){
    $.get('{{ url('/payrollsettings/specific_salary_component') }}/',{ specific_salary_component_id: specific_salary_component_id },function(data){

     $('#ename').val(data.name);
     $('#ecomment').val(data.comment);
     $('#ecategory').val(data.specific_salary_component_type_id);
     $('#eamount').val(data.amount);
      $('#egl_code').val(data.gl_code);
       $('#eproject_code').val(data.project_code);
       $('#etaxable').val(data.taxable);
        $('#etaxable_type').val(data.taxable_type);
        $('#eduration').val(data.duration);
        $('#eone_off').val(data.one_off);
      $("#eemps").find('option')
    .remove();
     if (data.type==1) {
    	$("#esscallowance").prop("checked", true);
    	$("#esscdeduction").prop("checked", false);
        $("#esscrebate").prop("checked", false);
    }else if(data.type==0){
    	$("#esscdeduction").prop("checked", true);
        $("#esscrebate").prop("checked", false);
    	$("#esscallowance").prop("checked", false);
    }else if(data.type==2){
        $("#esscdeduction").prop("checked", false);
        $("#esscrebate").prop("checked", true);
    	$("#esscallowance").prop("checked", false);
    }
    $("#eemps").append($('<option>', {value:data.user.id, text:data.user.name,selected:'selected'}));

     $('#eid').val(data.id);
    });
    $('#editSpecificSalaryComponentModal').modal();
  }
  function deleteSpecificSalaryComponent(specific_salary_component_id){
    $.get('{{ url('/payrollsettings/delete_specific_salary_component') }}/',{ specific_salary_component_id: specific_salary_component_id },function(data){
    	if (data=='success') {
 		toastr.success("Salary Component deleted successfully",'Success');
//  		$( "#ldr" ).load('{{url('payrollsettings/specific_salary_components')}}');
location.reload();
    	}else{
    		toastr.error("Error deleting Salary Component",'Success');
    	}

    });
  }



    /* Add a click handler to the rows - this could be used as a callback */
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
    	 		var specific_salary_component_id= $( this ).attr('id');
			   $.get('{{ url('/payrollsettings/delete_specific_salary_component') }}/',{ specific_salary_component_id: specific_salary_component_id },function(data){
				    });
			});
			var anSelected = fnGetSelected( oTable );
       		 $(anSelected).remove();
              alertify.success('Specific Salary Components deleted succesfully');
              location.reload();
            }, function () {
              alertify.error('Components not deleted');
            });


    } );
    $('#multi-active').click( function() {
    	 alertify.confirm('Are you sure you want to activate/Deactivate these components?', function () {
    	 	oTable.$('tr.row_selected').each(function() {
    	 		var specific_salary_component_id= $( this ).attr('id');
			   $.get('{{ url('/payrollsettings/change_specific_salary_component_status') }}/',{ specific_salary_component_id: specific_salary_component_id },function(data){
				    });
			});

              alertify.success('Specific Salary Components status changed succesfully');
              location.reload();
            }, function () {

            });


    } );

    $('#multi-taxable').click( function() {
    	 alertify.confirm('Are you sure you want to alter the taxable status of these components?', function () {
    	 	oTable.$('tr.row_selected').each(function() {
    	 		var specific_salary_component_id= $( this ).attr('id');
			    $.get('{{ url('/payrollsettings/change_specific_salary_component_taxable') }}/',{ specific_salary_component_id: specific_salary_component_id },function(data){
				    });
			});

              alertify.success('Salary Components Taxable status changed succesfully');
              location.reload();
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

  $('#emps').select2({
  	placeholder: "Employee Name",
  	 multiple: false,
  	id: function(bond){ return bond._id; },
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
  		salary_component_id= $(this).attr('id');

  		  $.get('{{ url('/payrollsettings/change_specific_salary_component_status') }}/',{ specific_salary_component_id: salary_component_id },function(data){
  		 	if (data==1) {
  		 		toastr.success("Salary Component Activated",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Salary Component Paused",'Success');
  		 	}
  		 	location.reload();
  		//  	$( "#ldr" ).load('{{url('payrollsettings/specific_salary_components')}}');
  		 });
  	});
  	 	});
 	$(function() {
  	 $('.sc-taxable').on('change', function() {
  		salary_component_id= $(this).attr('id');

  		 $.get('{{ url('/payrollsettings/change_specific_salary_component_taxable') }}/',{ specific_salary_component_id: salary_component_id },function(data){
  		 	if (data==1) {
  		 		toastr.success("Salary Component is now Taxable",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Salary Component is no more Taxable",'Success');
  		 	}
  		 	location.reload();
  		//  	$( "#ldr" ).load('{{url('payrollsettings/specific_salary_components')}}');
  		 });
  	});
  	 	});
  </script>

	<script>
		function calcDeductions() {
			alertify.confirm('Are you sure you want to Calculate?', function () {
				alertify.success('Processing this request. Please wait...');
				$.ajax({
					url: '{{route('calculate.lateness')}}',
					type: 'GET',
					success: function (data, textStatus, jqXHR) {
						alertify.success('Successfully Calculated Lateness to Specific Salary Component');

						setTimeout(function(){
							window.location.reload();
						},2000);
					},
					error: function (data, textStatus, jqXHR) {
						alertify.error('Something went wrong')
					}
				});
			}, function () {
				alertify.error('Cancelled')
			})
		}
	</script>

