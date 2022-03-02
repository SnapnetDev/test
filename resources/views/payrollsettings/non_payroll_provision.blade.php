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
                    <h3 class="panel-title">Employees For Non Payroll Provision</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" onclick="addMembers();">Add Employee</button>

                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped dataTable" id="">
                            <thead>
                                <tr>
                                    <th>Staff ID:</th>
                                    <th>Employee Name:</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($users as $user)

                                <tr>
                                    <td>{{$user->emp_num}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                role="menu">
                                                
                                                <a class="dropdown-item" id="{{$user->id}}"
                                                    onclick="removeEmployee(this.id)"><i class="fa fa-trash"
                                                        aria-hidden="true"></i>&nbsp;Remove Employee</a>

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
@include('payrollsettings.modals.addnonpayrollprovisionemployee')
{{-- edit grade modal --}}
<!-- End Page -->
<script type="text/javascript">
    $(document).ready( function () {
    $('.dataTable').DataTable();
    
     $('#members').select2({
     ajax: {
     delay: 250,
     processResults: function (data) {
          return {
    results: data
      };
    },
    url: function (params) {
    return '{{url('payrollsettings/non_payroll_provision_search')}}';
    }
    }
  });

} );
  	$(function() {

        $('#addNonPayrollProvisionEmployeesForm').submit(function(event){
		
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
		           $('#addNonPayrollProvisionEmployeesFormModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/non_payroll_provision')}}');
				// location.reload();

		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr.error(valchild[0]);
							});
							});
		        }
            });
            
            return  event.preventDefault();

		});
  });
  	


 function addMembers(){
    $.get('{{ url('/payrollsettings/get_non_payroll_provision') }}',function(data){
    
      $("#members").find('option')
    .remove();



     jQuery.each( data, function( i, val ) {
       $("#members").append($('<option>', {value:val.id, text:val.name,selected:'selected'}));
       // console.log(val.name);
              });
     
    });
    $('#addNonPayrollProvisionEmployeesFormModal').modal();
  }
  	
  function removeEmployee(employee_id){
    

    alertify.confirm('Are you sure you want to remove this Employee?', function () {

          $.get('{{ url('/payrollsettings/remove_non_payroll_provision_employee') }}/',{user_id:employee_id},function(data){
        if (data=='success') {
        toastr.success("Employee Removed successfully",'Success');
        $( "#ldr" ).load('{{url('payrollsettings/non_payroll_provision')}}');
        }else{
            toastr.error("Error removing Employee",'Success');
        }

    });
      }, function () {
          alertify.error('Employee not removed');
        });
  }
</script>