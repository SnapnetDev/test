<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Employee Reimbursement Settings')}}</li>
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
		              <h3 class="panel-title">Employee Reimbursement Types</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addEmployeeReimbursementTypeModal">Add Employee Reimbursement Type</button>

              			</div>
		            	</div>
		            <div class="panel-body">

	                  <table id="exampleTablePagination" data-toggle="table"
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th >Name:</th>
		                        <th >Workflow:</th>
                                  <th >Created By:</th>
                                  <th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
                                @foreach($employee_reimbursement_types as $ert)
                                    <tr>
                                        <td>{{$ert->name}}</td>
                                        <td>{{$ert->workflow?$ert->workflow->name:''}}</td>
                                        <td>{{$ert->user?$ert->user->name:''}}</td>
                                        <td><a class="" title="edit" class="btn btn-icon btn-info" id="{{$ert->id}}" onclick="prepareEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$ert->id}}" onclick="deletedt(this.id);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach

		                    </tbody>
	                  </table>
	          		</div>
	          		</div>
	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">

	    	</div>
		</div>
	  </div>
{{-- Add IP Modal --}}
	   @include('settings.employeereimbursementsettings.modals.addemployeereimbursementtype')
	  {{-- edit IP modal --}}
	   @include('settings.employeereimbursementsettings.modals.editemployeereimbursementtype')
<script type="text/javascript">
  $(function() {

      $('#addEmployeeReimbursementTypeForm').submit(function(e){
          e.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{url('employee_reimbursements')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){

                  toastr["success"]("Changes saved successfully",'Success');
                  $('#addEmployeeReimbursementTypeModal').modal('toggle');
                  $( "#ldr" ).load('{{url('employee_reimbursements/settings')}}');
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

      $('#editEmployeeReimbursementTypeForm').submit(function(e){
          e.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{url('employee_reimbursements')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){

                  toastr["success"]("Changes saved successfully",'Success');
                  $('#editEmployeeReimbursementTypeModal').modal('toggle');
                  $( "#ldr" ).load('{{url('employee_reimbursements/settings')}}');
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
  function prepareEditData(employee_reimbursement_type_id){
      $.get('{{ url('/employee_reimbursements/employee_reimbursement_type') }}/'+employee_reimbursement_type_id,function(data){
          // console.log(data);
          $('#employee_reimbursement_type_id').val(data.id);
          $('#editname').val(data.name);
          $('#editworkflow_id').val(data.workflow_id);
      });
      $('#editEmployeeReimbursementTypeModal').modal();
  }
  function deletedt(employee_reimbursement_type_id){
      alertify.confirm('Are you sure you want to delete this Expense Reimbursement Type?', function () {


          $.get('{{ url('employee_reimbursements/delete_employee_reimbursement_type') }}',{employee_reimbursement_type_id:employee_reimbursement_type_id},function(data){
              if (data=='success') {
                  toastr["success"]("Expense Reimbursement Type deleted successfully",'Success');

                  location.reload();
              }else{
                  toastr["error"]("Error deleting Expense Reimbursement Type",'Success');
              }

          });
      }, function () {
          alertify.error('Expense Reimbursement Type not deleted');
      });
  }

</script>
