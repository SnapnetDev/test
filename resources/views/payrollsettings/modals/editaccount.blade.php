<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editAccountModal" aria-hidden="true" aria-labelledby="editAccountModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog modal-lg">

	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Account</h4>
	        </div>
          <form class="form-horizontal" id="editAccountForm"  method="POST">
            <div class="modal-body">

                  	@csrf
                <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                                    <h4 class="example-title">Name</h4>
                                    <input required type="text" name="name" id="editcaname" class="form-control">
                                  </div>
                    </div>
                    <div class="col-md-6">
                            <div class="form-group">
                                    <h4 class="example-title">Code</h4>
                                    <input required type="text" name="code" id="editcacode" class="form-control">
                                  </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                        <h4 class="example-title">Description</h4>
                                        <textarea name="description" id="editcadescription" class="form-control"></textarea>
                                      </div>
                        </div>
                </div>
                <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label class="example-title" for="account_type">Type</label>

                                        <select required name="account_type" style="width:100%;" id="editcaaccount_type" class="form-control " >
                                          <option  value='0'>Credit</option>
                                          <option  value='1'>Debit</option></select>
                                      </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label class="example-title" for="display">Display</label>

                                        <select required name="display" style="width:100%;" id="editcadisplay" class="form-control " >

                                          <option  value='1'>Cummulative</option>
                                          <option  value='2'>Spread</option>
                                          <option  value='3'>Individual</option></select>
                                      </div>
                        </div>
                </div>
                <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label class="example-title" for="source">Source</label>

                                        <select required  name="source" style="width:100%;" id="editcasource" class="form-control " >
                                           <option  value='1'>Salary Component</option>
                                          <option  value='2'>Specific Salary Component Type</option>
                                          <option  value='3'>Payroll Component</option></select>
                                          <option  value='4'>Amount</option></select>
                                      </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label class="example-title" for="editcasalary_component_constant">Salary Components</label>
                                        <select  name="salary_component_constant" style="width:100%;" id="editcasalary_component_constant" class="form-control " >

                                          @foreach ($components as $key=>$component)
                                            <option  value='{{$key}}'>{{$component}}</option>
                                          @endforeach
                                          </select>
                                      </div>
                        </div>
                </div>
                <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label class="example-title" for="other_constant">Payroll Components</label>

                                        <select  name="other_constant" style="width:100%;" id="editcaother_constant" class="form-control " >
                                           <option  value='gross_pay'>Gross Pay</option>
                                          <option  value='netpay'>Net Pay</option>
                                          <option  value='basic_pay'>Basic Pay</option>
                                          <option  value='paye'>PAYE</option>
                                          <option  value='union_dues'>Union Dues</option>
                                        </select>
                                      </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label class="example-title" for="specific_salary_component_type_id">Specific Salary Component Types</label>

                                        <select  name="specific_salary_component_type_id" style="width:100%;" id="editcaspecific_salary_component_type_id" class="form-control " >
                                          <option  value=''>Select a component type</option>
                                          @foreach ($ssc_types as $type)
                                            <option  value='{{$type->id}}'>{{$type->name}}</option>
                                          @endforeach
                                          </select>
                                      </div>
                        </div>
                </div>
                
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="example-title" for="editcauses_group">Uses Group</label>

                                <select required="" name="uses_group" style="width:100%;" id="editcauses_group" class="form-control " >
                                   <option  value='0'>No</option>
                                  <option  value='1'>Yes</option>
                                </select>
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="example-title" for="editcagroup_id">Groups</label>

                                <select  name="group_id" style="width:100%;" id="editcagroup_id" class="form-control " >
                                  <option  value='0'>Select Group</option>
                                    @foreach ($user_groups as $group)
                                    <option  value='{{$group->id}}'>{{$group->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                        </div>
                    </div>
                <ul id="editcompcont" style="border: #ddd 1px solid; padding-inline-start: 0px;padding-top: 10px;">
                        <label for="">Extra Components</label>
                    </ul>
                    <button type="button" id="addeditComponent" name="button" class="btn btn-primary">New Extra Component</button>
                <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label class="example-title" for="editcanationality_display">Nationality Display</label>

                                        <select  name="nationality_display" style="width:100%;" id="editcanationality_display" class="form-control " >
                                           <option  value='1'>All</option>
                                          <option  value='2'>Expatriates</option>
                                          <option  value='3'>Nationals</option></select>
                                      </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label class="example-title" for="salary_charge">Salary/Charge</label>

                                        <select  name="salary_charge" style="width:100%;" id="editcasalary_charge" class="form-control " >
                                           <option  value='salary'>Salary</option>
                                          <option  value='charge'>Charge</option>
                                        </select>
                                      </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                                <label class="example-title" for="editcanon_payroll_provision">Non Payroll Provision</label>

                                <select required name="non_payroll_provision" style="width:100%;" id="editcanon_payroll_provision" class="form-control " >
                                  <option  value='1'>Yes</option>
                                  <option  value='0'>No</option></select>
                              </div>
                </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label class="example-title" for="status">Status</label>

                                        <select required name="status" style="width:100%;" id="editcastatus" class="form-control " >
                                          <option  value='1'>Active</option>
                                          <option  value='0'>Inactive</option></select>
                                      </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                                    <h4 class="example-title">Percentage</h4>
                                    <input type="text" name="formula" id="editcaformula" class="form-control">
                                  </div>
                    </div>

            </div>
















                    <input type="hidden" name="type" value="chart_of_accounts">
                    <input type="hidden" name="account_id" id="editcaid">

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
