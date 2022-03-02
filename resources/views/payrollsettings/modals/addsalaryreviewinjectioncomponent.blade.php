<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addAccountModal" aria-hidden="true" aria-labelledby="addAccountModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog modal-lg">

	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add Salary Injection Component</h4>
	        </div>
          <form class="form-horizontal" id="addSalaryReviewInjectionComponentForm"  method="POST">
            <div class="modal-body">

                  	@csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4 class="example-title">Component Type</h4>
                                <input required type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4 class="example-title">Code</h4>
                                <input required type="text" name="code" class="form-control">
                              </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4 class="example-title">Description</h4>
                                <textarea name="description" class="form-control"></textarea>
                              </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="example-title" for="source">Source</label>

                                <select  name="component_type" style="width:100%;" id="source" class="form-control " >
                                   <option  value='1'>Salary Component</option>
                                  <option  value='2'>Specific Salary Component Type</option>
                                  <option  value='3'>Payroll Component</option></select>
                              </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="example-title" for="specific_salary_component_type_id">Salary Components</label>

                                <select  name="salary_component_constant" style="width:100%;" id="salary_component_constant" class="form-control " >

                                  @foreach ($components as $key=>$component)
                                    <option  value='{{$key}}'>{{$component}}</option>
                                  @endforeach
                                  </select>
                              </div>
                            <div class="form-group">
                                <label class="example-title" for="other_constant">Payroll Components</label>

                                <select  name="other_constant" style="width:100%;" id="other_constant" class="form-control " >
                                    <option  value='gross_pay'>Gross Pay</option>
                                    <option  value='netpay'>Net Pay</option>
                                    <option  value='basic_pay'>Basic Pay</option>
                                    <option  value='paye'>PAYE</option>
                                    <option  value='union_dues'>Union Dues</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="example-title" for="specific_salary_component_type_id">Specific Salary Component Types</label>

                                <select  name="specific_salary_component_type_id" style="width:100%;" id="specific_salary_component_type_id" class="form-control " >
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
                                <label class="example-title" for="status">Status</label>

                                <select required="" name="status" style="width:100%;" id="status" class="form-control " >
                                   <option  value='0'>Inactive</option>
                                  <option  value='1'>Active</option>
                                </select>
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="example-title" for="injection_type">Injection Type</label>

                                <select  name="injection_type" style="width:100%;" id="injection_type" class="form-control " >
                                  <option  value='0'>Difference</option>
                                    <option  value='1'>Full</option>
                                </select>
                              </div>
                        </div>
                    </div>
                    <ul id="compcont" style="border: #ddd 1px solid; padding-inline-start: 0px;padding-top: 10px;">
                        <label for="">Extra Components</label>
                    </ul>
                    <button type="button" id="addComponent" name="button" class="btn btn-primary">New Extra Component</button>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="example-title" for="display">Nationality Display</label>

                                <select required="" name="nationality_display" style="width:100%;" id="nationality_display" class="form-control " >
                                   <option  value='1'>All</option>
                                  <option  value='2'>Expatriates</option>
                                  <option  value='2'>Nationals</option></select>
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="example-title" for="salary_charge">Salary/Charge</label>

                                <select  name="salary_charge" style="width:100%;" id="salary_charge" class="form-control " >
                                   <option  value='salary'>Salary</option>
                                  <option  value='charge'>Charge</option>
                                </select>
                              </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label class="example-title" for="non_payroll_provision">Non Payroll Provision</label>

                                    <select required name="non_payroll_provision" style="width:100%;" id="non_payroll_provision" class="form-control " >
                                      <option  value='1'>Yes</option>
                                      <option  value='0'>No</option></select>
                                  </div>
                    </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label class="example-title" for="status">Status</label>

                                        <select required name="status" style="width:100%;" id="status" class="form-control " >
                                          <option  value='1'>Active</option>
                                          <option  value='0'>Inactive</option></select>
                                      </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4 class="example-title">Percentage</h4>
                                <input type="text" name="formula" class="form-control">
                              </div>

                        </div>

                    </div>




                    <input type="hidden" name="type" value="chart_of_accounts">

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
