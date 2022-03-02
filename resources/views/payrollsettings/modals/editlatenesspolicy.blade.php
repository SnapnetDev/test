<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editLatenessPolicyModal" aria-hidden="true" aria-labelledby="editLatenessPolicyModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Lateness Policy</h4>
	        </div>
          <form class="form-horizontal" id="editLatenessPolicyForm"  method="POST">
            <div class="modal-body">         
                
                  	@csrf
                   <div class="form-group" >
                      <h4>Deduction Type</h4>
                      <input type="radio" id="edit_percentage" name="deduction_type" value="1"> Percentage
                      <input type="radio" id="edit_amount" name="deduction_type" value="0"> Amount
                    </div>
                  	<div class="form-group">
                  		<h4 class="example-title">Policy Name</h4>
                  		<input type="text" id="edit_policy_name" name="policy_name" class="form-control">
                  	</div>
                    <div class="form-group">
                      <h4 class="example-title">Grace Period</h4>
                      <input type="text" id="edit_late_minute" name="late_minute" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Deduction</h4>
                      <input type="text" id="edit_deduction" name="deduction" class="form-control">
                    </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="example-title" for="taxable">Specific Salary Component Type</label>
                            <select required="" id="edit_ssctype_id"  name="specific_salary_component_type_id" style="width:100%;" class="form-control " >
                                @foreach($sal_comp_types as $ssc)
                                    <option  value='{{$ssc->id}}'>{{$ssc->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="example-title">Which Payroll should use?</label>
                            <select required="" id="edit_payroll"  name="payroll" style="width:100%;" class="form-control " >
                                <option  value='normal'>Normal</option>
                                <option  value='attendance'>Attendance</option>
                                <option  value='all'>All</option>
                            </select>
                        </div>
                    </div>
                </div>


                    <input type="hidden" name="type" value="lateness_policy">
                     <input type="hidden" id="editid" name="lateness_policy_id">
                          
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              	
                  <div class="form-group">
                    
                    <button type="submit" class="btn btn-info pull-left">Save</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
             </form>
	       </div>
	      
	    </div>
	  </div>