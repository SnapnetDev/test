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
		              <h3 class="panel-title">Chart of Accounts</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addAccountModal">Add Account</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            <div class="table-responsive">
	                  <table  class="table table-striped " id="dataTable">
		                    <thead>
		                      <tr>
		                      	<th>Position:</th>
		                        <th>Name:</th>
		                        <th>Code:</th>
		                        <th>Description:</th>
		                        <th> Display(Cummulative/Spread)</th>
		                         <th>Type(Debit/Credit):</th>
		                         <th>Source</th>
		                         <th>Component</th>


		                          <th>Status</th>
		                      </tr>
		                    </thead>
		                    <tbody>

		                    	@forelse($cas as $account)

		                    	<tr data-index='{{$account->id}}' data-position='{{$account->position}}' style="cursor: pointer;" title="drag to re-order" data-toggle="tooltip" data-placement="top">
		                    		<td>{{$account->position}}</td>
		                    		<td>{{$account->name}}</td>
		                    		<td>{{$account->code}}</td>
		                    		<td>{{$account->description}}</td>
		                    		<td>{{ $account->display == 1 ? 'Cummulative' :( $account->display == 2? ' Spread':($account->display == 3? ' Individual':'') ) }}</td>
		                    		<td>{{ $account->type == 1 ? 'Debit' : 'Credit' }}</td>
		                    		<td>{{ $account->source == 1 ? 'Salary Component' :( $account->source == 2? ' Specific Salary Component Type':($account->source == 3? ' Payroll Component':'') ) }}</td>
		                    		<td>
		                    			@if ($account->source==1)
		                    				{{$account->salary_component_constant}}
		                    			@elseif($account->source==2)
		                    				{{$account->ssc_type?$account->ssc_type->name:''}}
		                    			@elseif($account->source==3)
											{{$account->other_constant}}
		                    			@endif
		                    		</td>
		                    		<td>{{ $account->status == 1 ? 'Active' : 'Inactive' }}</td>


		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$account->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Account</a>
				                       <a class="dropdown-item" id="{{$account->id}}" onclick="deleteAccount(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Account</a>

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
	   @include('payrollsettings.modals.addaccount')
	  {{-- edit grade modal --}}
	   @include('payrollsettings.modals.editaccount')
      <!-- End Page -->
      <script type="text/javascript" src="{{ asset('global/vendor/jquery-ui/jquery-ui.min.js')}}"></script>
      {{-- <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script> --}}
	    <script type="text/javascript">
	    	 $(document).ready( function () {
    // $('#dataTable').DataTable();

} );
  	$(function() {


  	$(document).on('submit','#addAccountForm',function(event){
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
		           $('#addAccountModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/chart_of_accounts')}}');
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

		            toastr.success("Changes saved successfully",'Success');
		            $('#editAccountModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/chart_of_accounts')}}');
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

  // 	$(function() {
  // 	$(document).on('click','.sc-status',function(event){

  // 		salary_component_id= $(this).attr('id');

  // 		 $.get('{{ url('/payrollsettings/change_salary_component_status') }}/',{ salary_component_id: salary_component_id },function(data){
  // 		 	if (data==1) {

  // 		 		toastr.success("Salary Component Enabled",'Success');
  // 		 		$(this).removeClass('btn-warning');
  // 		 		$(this).addClass('btn-success');
  // 		 	}
  // 		 	if(data==2){
  // 		 		toastr.warning("Salary Component Disabled",'Success');
  // 		 		$(this).addClass('btn-warning');
  // 		 		$(this).removeClass('btn-success');
  // 		 	}

  // 		 });

  // 	});
  // });

  	function prepareEditData(account_id){
    $.get('{{ url('/payrollsettings/chart_of_account') }}/',{ account_id: account_id },function(data){

     $('#editcaname').val(data.name);
     $('#editcacode').val(data.code);
     $('#editcadescription').val(data.description);
     $('#editcaaccount_type').val(data.type);
      $('#editcadisplay').val(data.display);
      $('#editcastatus').val(data.status);
      $('#editcasource').val(data.source);
      $('#editcasalary_component_constant').val(data.salary_component_constant);
      $('#editcaother_constant').val(data.other_constant);
      $('#editcaamount').val(data.amount);
      $('#editcaspecific_salary_component_type_id').val(data.specific_salary_component_type_id);
      $('#editcauses_group').val(data.uses_group);
      $('#editcagroup_id').val(data.group_id);
      $('#editcanationality_display').val(data.nationality_display);
      $('#editcaformula').val(data.formula);
      $('#editcasalary_charge').val(data.salary_charge);
      $('#editcanon_payroll_provision').val(data.non_payroll_provision);
      var editcompcont = $('#editcompcont');
      jQuery.each( data.account_extra_components, function( i, val ) {
        $(' <li style="list-style:none;" id="comp_'+val.id+'"><input  type="hidden" class="extra_component_ids" name="extra_component_id[]" value="0"><div class="form-cont" ><div class="col-md-3"><div class="form-group comp_type"> <label for="">Source</label> <select class="form-control select-type " name="comp_source[]"><option value="1">Salary Component</option><option value="2">Specific Salary Component</option><option value="3">Payroll Component</option><option value="4">Amount</option></select> </div></div><div class="col-md-3"><div class="form-group comp_operator"> <label for="">Operator</label> <select class="form-control select-operator "name="comp_operator[]"><option value="addition">Addition</option><option value="subtraction">Subtraction</option></select> </div></div><div  class="col-md-3"><div class="form-group salary_comp-div"> <label for="">Salary Components</label> <select class="form-control salary_components" name="comp_salary_component[]">@foreach ($components as $key=>$component)<option  value="{{$key}}">{{$component}}</option>@endforeach </select> </div><div class="form-group specific_salary_comp-div"> <label for="">Specific Salary Component Types</label><select class="form-control specific_salary_component_types" name="comp_specific_salary_component_type_id[]">@foreach ($ssc_types as $type)<option  value="{{$type->id}}">{{$type->name}}</option>@endforeach </select> </div><div class="form-group payroll_comp-div"> <label for="">Payroll Component</label><select class="form-control payroll_components" name="payroll_constant[]"> <option  value="gross_pay">Gross Pay</option><option  value="netpay">Net Pay</option><option  value="basic_pay">Basic Pay</option> <option  value="paye">PAYE</option><option  value="union_dues">Union Dues</option> </select> </div><div class="form-group amount_comp-div"> <label for="">Amount</label><input type="number" step="0.01" name="amount[]" class="form-control amount_components"> </div></div><div class="col-md-3"><div class="form-group comp_percentage"> <label for="">Percentage</label><input type="number" step="0.0001" name="comp_percentage[]" class="form-control select-percentage"></div></div><div class="form-group" style="padding-left: .9375rem;"> <button  type="button" class="btn btn-primary " id="editremComponent">Remove Component</button> </div></div><hr></li>').appendTo(editcompcont);
        $('#comp_'+val.id).find('.select-type').val(val.source);
                     $('#comp_'+val.id).find('.extra_component_ids').val(val.id);
                     $('#comp_'+val.id).find('.select-operator').val(val.operator);
                      $('#comp_'+val.id).find('.select-percentage').val(val.percentage);
                    if (val.source==3)
                    {
                      $('#comp_'+val.id).find('.salary_comp-div').find('.comp_salary_component').attr("disabled",true);
                      $('#comp_'+val.id).find('.salary_comp-div').hide();
                      $('#comp_'+val.id).find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);
                      $('#comp_'+val.id).find('.specific_salary_comp-div').hide();
                       $('#comp_'+val.id).find('.payroll_comp-div').find('.payroll_constant').removeAttr("disabled");
                     $('#comp_'+val.id).find('.payroll_comp-div').show();
                     $('#comp_'+val.id).find('.payroll_components').val(val.payroll_constant);
                     $('#comp_'+val.id).find('.amount_comp-div').hide();
                        $('#comp_'+val.id).find('.amount_comp-div').find('.amount').attr("disabled",true);


                    }

                  if (val.source==2)
                    {
                      $('#comp_'+val.id).find('.salary_comp-div').find('.comp_salary_component').attr("disabled",true);
                      $('#comp_'+val.id).find('.salary_comp-div').hide();
                      $('#comp_'+val.id).find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);
                      $('#comp_'+val.id).find('.payroll_comp-div').hide();
                       $('#comp_'+val.id).find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').removeAttr("disabled");
                     $('#comp_'+val.id).find('.specific_salary_comp-div').show();
                     $('#comp_'+val.id).find('.specific_salary_component_types').val(val.specific_salary_component_type_id);
                     $('#comp_'+val.id).find('.amount_comp-div').hide();
                        $('#comp_'+val.id).find('.amount_comp-div').find('.amount').attr("disabled",true);

                    }
                    if (val.source==1)
                    {
                      $('#comp_'+val.id).find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);
                      $('#comp_'+val.id).find('.payroll_comp-div').hide();
                      $('#comp_'+val.id).find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);

                        $('#comp_'+val.id).find('.salary_comp-div').find('.comp_salary_component').removeAttr("disabled");
                        $('#comp_'+val.id).find('.salary_comp-div').show();
                     $('#comp_'+val.id).find('.specific_salary_comp-div').hide();
                     $('#comp_'+val.id).find('.salary_components').val(val.salary_component_constant);
                      $('#comp_'+val.id).find('.amount_comp-div').hide();
                        $('#comp_'+val.id).find('.amount_comp-div').find('.amount').attr("disabled",true);


                    }
                    if (val.source==4)
                    {
                      $('#comp_'+val.id).find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);
                      $('#comp_'+val.id).find('.payroll_comp-div').hide();
                      $('#comp_'+val.id).find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);

                        $('#comp_'+val.id).find('.salary_comp-div').find('.comp_salary_component').removeAttr("disabled");
                        $('#comp_'+val.id).find('.salary_comp-div').hide();
                     $('#comp_'+val.id).find('.specific_salary_comp-div').hide();
                      $('#comp_'+val.id).find('.amount_comp-div').show();
                        $('#comp_'+val.id).find('.amount_comp-div').find('.amount').removeAttr("disabled");
                         $('#comp_'+val.id).find('.amount_components').val(val.amount);

                    }

    //    $("#editpcexemptions").append($('<option>', {value:val.id, text:val.name,selected:'selected'}));
       // console.log(val.name);
              });
     $('#editcaid').val(data.id);
    });
    $('#editAccountModal').modal();
  }

  function deleteAccount(account_id){
    $.get('{{ url('/payrollsettings/delete_account') }}/',{account_id:account_id},function(data){
    	if (data=='success') {
 		toastr.success("Account deleted successfully",'Success');
 		$( "#ldr" ).load('{{url('payrollsettings/chart_of_accounts')}}');
    	}else{
    		toastr.error("Error deleting Account",'Success');
    	}

    });
  }





  </script>
<script type="text/javascript">
        $(document).ready(function () {
           $('#dataTable tbody').sortable({
               update: function (event, ui) {
                   $(this).children().each(function (index) {
                        if ($(this).attr('data-position') != (index+1)) {
                            $(this).attr('data-position', (index+1)).addClass('updated');
                        }
                   });

                   saveNewPositions();
               }
           });
        });

        function saveNewPositions() {
            var positions = [];
            $('.updated').each(function () {
               positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
               $(this).removeClass('updated');
            });

            $.ajax({
               url: '{{url('payrollsettings')}}',
               method: 'POST',
               dataType: 'text',
               data: {
               	_token:'{{csrf_token()}}',
               		type:'chart_of_accounts_positions',

                   positions: positions
               }, success: function (response) {
                    console.log(response);
               }
            });
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {


          var compcont = $('#compcont');
                var i = $('#compcont li').length + 1;

                $('#addComponent').on('click', function() {
                  //console.log('working');
                        $(' <li style="list-style:none;" ><input  type="hidden" name="extra_component_id[]" value="0"><div class="form-cont" ><div class="col-md-3"><div class="form-group comp_type"> <label for="">Source</label> <select class="form-control select-type " name="comp_source[]"><option value="1">Salary Component</option><option value="2">Specific Salary Component</option><option value="3">Payroll Component</option><option value="4">Amount</option></select> </div></div><div class="col-md-3"><div class="form-group comp_operator"> <label for="">Operator</label> <select class="form-control select-operator "name="comp_operator[]"><option value="addition">Addition</option><option value="subtraction">Subtraction</option></select> </div></div><div  class="col-md-3"><div class="form-group salary_comp-div"> <label for="">Salary Components</label> <select class="form-control salary_components" name="comp_salary_component[]">@foreach ($components as $key=>$component)<option  value="{{$key}}">{{$component}}</option>@endforeach </select> </div><div class="form-group specific_salary_comp-div"> <label for="">Specific Salary Component Types</label><select class="form-control specific_salary_component_types" name="comp_specific_salary_component_type_id[]">@foreach ($ssc_types as $type)<option  value="{{$type->id}}">{{$type->name}}</option>@endforeach </select> </div><div class="form-group payroll_comp-div"> <label for="">Payroll Component</label><select class="form-control payroll_components" name="payroll_constant[]"> <option  value="gross_pay">Gross Pay</option><option  value="netpay">Net Pay</option><option  value="basic_pay">Basic Pay</option> <option  value="paye">PAYE</option><option  value="union_dues">Union Dues</option> </select> </div><div class="form-group amount_comp-div"> <label for="">Amount</label><input type="number" step="0.01" name="amount[]" class="form-control amount_components"> </div></div><div class="col-md-3"><div class="form-group comp_percentage"> <label for="">Percentage</label><input type="number" step="0.0001" name="comp_percentage[]" class="form-control select-percentage"></div></div><div class="form-group" style="padding-left: .9375rem;"> <button  type="button" class="btn btn-primary " id="remComponent">Remove Component</button> </div></div><hr></li>').appendTo(compcont);
                        //console.log('working'+i);

                       $('#compcont li').last().find('.specific_salary_comp-div').hide();
                       $('#compcont li').last().find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);
                        $('#compcont li').last().find('.payroll_comp-div').hide();
                       $('#compcont li').last().find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);

                        $('#compcont li').last().find('.amount_comp-div').hide();
                       $('#compcont li').last().find('.amount_comp-div').find('.amount').attr("disabled",true);
                        i++;
                        return false;
                });

                $(document).on('click',"#remComponent",function() {
                  //console.log('working'+i);
                        if( i > 1 ) {
                           console.log('working'+i);
                                $(this).parents('li').remove();
                                i--;
                        }
                        return false;
            });
                $(document).on('change',".select-type",function() {


                 if (this.value==3)
                    {
                      $(this).parents('li').find('.salary_comp-div').find('.comp_salary_component').attr("disabled",true);
                      $(this).parents('li').find('.salary_comp-div').hide();
                      $(this).parents('li').find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);
                      $(this).parents('li').find('.specific_salary_comp-div').hide();
                       $(this).parents('li').find('.payroll_comp-div').find('.payroll_constant').removeAttr("disabled");
                     $(this).parents('li').find('.payroll_comp-div').show();
                     $('#compcont li').last().find('.amount_comp-div').hide();
                       $('#compcont li').last().find('.amount_comp-div').find('.amount').attr("disabled",true);

                    }

                  if (this.value==2)
                    {
                      $(this).parents('li').find('.salary_comp-div').find('.comp_salary_component').attr("disabled",true);
                      $(this).parents('li').find('.salary_comp-div').hide();
                      $(this).parents('li').find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);
                      $(this).parents('li').find('.payroll_comp-div').hide();
                       $(this).parents('li').find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').removeAttr("disabled");
                     $(this).parents('li').find('.specific_salary_comp-div').show();
                     $('#compcont li').last().find('.amount_comp-div').hide();
                       $('#compcont li').last().find('.amount_comp-div').find('.amount').attr("disabled",true);

                    }
                    if (this.value==1)
                    {
                      $(this).parents('li').find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);
                      $(this).parents('li').find('.payroll_comp-div').hide();
                      $(this).parents('li').find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);

                        $(this).parents('li').find('.salary_comp-div').find('.comp_salary_component').removeAttr("disabled");
                        $(this).parents('li').find('.salary_comp-div').show();
                     $(this).parents('li').find('.specific_salary_comp-div').hide();
                     $('#compcont li').last().find('.amount_comp-div').hide();
                       $('#compcont li').last().find('.amount_comp-div').find('.amount').attr("disabled",true);

                    }
                    if (this.value==4)
                    {
                      $(this).parents('li').find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);
                      $(this).parents('li').find('.payroll_comp-div').hide();
                      $(this).parents('li').find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);

                        $(this).parents('li').find('.salary_comp-div').find('.comp_salary_component').attr("disabled",true);
                        $(this).parents('li').find('.salary_comp-div').hide();
                     $(this).parents('li').find('.specific_salary_comp-div').hide();
                     $(this).parents('li').find('.amount_comp-div').show();
                       $(this).parents('li').find('.amount_comp-div').find('.amount').removeAttr("disabled");

                    }


                  });
        });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {


              var compcont = $('#editcompcont');
                    var i = $('#editcompcont li').length + 1;

                    $('#addeditComponent').on('click', function() {
                      //console.log('working');
                            $(' <li style="list-style:none;" ><input  type="hidden" name="extra_component_id[]" value="0"><div class="form-cont" ><div class="col-md-3"><div class="form-group comp_type"> <label for="">Source</label> <select class="form-control select-type " name="comp_source[]"><option value="1">Salary Component</option><option value="2">Specific Salary Component</option><option value="3">Payroll Component</option><option value="4">Amount</option></select> </div></div><div class="col-md-3"><div class="form-group comp_operator"> <label for="">Operator</label> <select class="form-control select-operator "name="comp_operator[]"><option value="addition">Addition</option><option value="subtraction">Subtraction</option></select> </div></div><div  class="col-md-3"><div class="form-group salary_comp-div"> <label for="">Salary Components</label> <select class="form-control salary_components" name="comp_salary_component[]">@foreach ($components as $key=>$component)<option  value="{{$key}}">{{$component}}</option>@endforeach </select> </div><div class="form-group specific_salary_comp-div"> <label for="">Specific Salary Component Types</label><select class="form-control specific_salary_component_types" name="comp_specific_salary_component_type_id[]">@foreach ($ssc_types as $type)<option  value="{{$type->id}}">{{$type->name}}</option>@endforeach </select> </div><div class="form-group payroll_comp-div"> <label for="">Payroll Component</label><select class="form-control payroll_components" name="payroll_constant[]"> <option  value="gross_pay">Gross Pay</option><option  value="netpay">Net Pay</option><option  value="basic_pay">Basic Pay</option> <option  value="paye">PAYE</option><option  value="union_dues">Union Dues</option> </select> </div><div class="form-group amount_comp-div"> <label for="">Amount</label><input type="number" step="0.01" name="amount[]" class="form-control amount_components"> </div></div><div class="col-md-3"><div class="form-group comp_percentage"> <label for="">Percentage</label><input type="number" step="0.0001" name="comp_percentage[]" class="form-control select-percentage"></div></div><div class="form-group" style="padding-left: .9375rem;"> <button  type="button" class="btn btn-primary " id="editremComponent">Remove Component</button> </div></div><hr></li>').appendTo(compcont);
                            //console.log('working'+i);

                           $('#editcompcont li').last().find('.specific_salary_comp-div').hide();
                           $('#editcompcont li').last().find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);
                            $('#editcompcont li').last().find('.payroll_comp-div').hide();
                           $('#editcompcont li').last().find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);
                            $('#editcompcont li').last().find('.amount_comp-div').hide();
                       $('#editcompcont li').last().find('.amount_comp-div').find('.amount').attr("disabled",true);
                            i++;
                            return false;
                    });

                    $(document).on('click',"#editremComponent",function() {
                      //console.log('working'+i);
                            if( i > 1 ) {
                               console.log('working'+i);
                                    $(this).parents('li').remove();
                                    i--;
                            }
                            return false;
                });
                    $(document).on('change',".select-type",function() {


                     if (this.value==3)
                    {
                      $(this).parents('li').find('.salary_comp-div').find('.comp_salary_component').attr("disabled",true);
                      $(this).parents('li').find('.salary_comp-div').hide();
                      $(this).parents('li').find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);
                      $(this).parents('li').find('.specific_salary_comp-div').hide();
                       $(this).parents('li').find('.payroll_comp-div').find('.payroll_constant').removeAttr("disabled");
                     $(this).parents('li').find('.payroll_comp-div').show();
                     $(this).parents('li').find('.amount_comp-div').hide();
                       $(this).parents('li').find('.amount_comp-div').find('.amount').attr("disabled",true);

                    }

                  if (this.value==2)
                    {
                      $(this).parents('li').find('.salary_comp-div').find('.comp_salary_component').attr("disabled",true);
                      $(this).parents('li').find('.salary_comp-div').hide();
                      $(this).parents('li').find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);
                      $(this).parents('li').find('.payroll_comp-div').hide();
                       $(this).parents('li').find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').removeAttr("disabled");
                     $(this).parents('li').find('.specific_salary_comp-div').show();
                     $(this).parents('li').find('.amount_comp-div').hide();
                       $(this).parents('li').find('.amount_comp-div').find('.amount').attr("disabled",true);

                    }
                    if (this.value==1)
                    {
                      $(this).parents('li').find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);
                      $(this).parents('li').find('.payroll_comp-div').hide();
                      $(this).parents('li').find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);

                        $(this).parents('li').find('.salary_comp-div').find('.comp_salary_component').removeAttr("disabled");
                        $(this).parents('li').find('.salary_comp-div').show();
                     $(this).parents('li').find('.specific_salary_comp-div').hide();
                     $(this).parents('li').find('.amount_comp-div').hide();
                       $(this).parents('li').find('.amount_comp-div').find('.amount').attr("disabled",true);

                    }
                    if (this.value==4)
                    {
                      $(this).parents('li').find('.payroll_comp-div').find('.payroll_constant').attr("disabled",true);
                      $(this).parents('li').find('.payroll_comp-div').hide();
                      $(this).parents('li').find('.specific_salary_comp-div').find('.comp_specific_salary_component_type_id').attr("disabled",true);

                        $(this).parents('li').find('.salary_comp-div').find('.comp_salary_component').attr("disabled",true);
                        $(this).parents('li').find('.salary_comp-div').hide();
                     $(this).parents('li').find('.specific_salary_comp-div').hide();
                     $(this).parents('li').find('.amount_comp-div').show();
                       $(this).parents('li').find('.amount_comp-div').find('.amount').removeAttr("disabled");

                    }



                      });
            });
            </script>

